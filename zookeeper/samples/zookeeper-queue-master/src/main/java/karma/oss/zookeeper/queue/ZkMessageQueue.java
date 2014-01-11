/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

package karma.oss.zookeeper.queue;

import java.util.*;
import java.util.concurrent.CountDownLatch;
import java.util.concurrent.atomic.AtomicReference;

import java.net.InetAddress;
import java.net.UnknownHostException;

import com.google.common.base.Optional;
import org.apache.commons.codec.binary.StringUtils;
import org.apache.zookeeper.*;
import org.apache.zookeeper.data.Stat;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;
import org.apache.zookeeper.data.ACL;

import static java.util.Map.Entry;

/**
 *
 * A <a href="package.html">protocol to implement a distributed queue</a>.
 * May loose strict FIFO in distributed scenario where because of race conditions,
 * a rejected element (which needs to be reprocessed) is not picked up and an element
 * after it is picked up.
 *
 * This class does not remove elements from the queue unless removeElement() is called
 *
 * XXX: Need to implement message grouping to enable strict FIFO for a group. Unless that happens,
 * XXX: there are no guarantees on ordered execution
 */

public class ZkMessageQueue {
    private static final Logger LOG = LoggerFactory.getLogger(ZkMessageQueue.class);

    public static class Element<T> {
    public final String elementId;
    public final T element;

        private Element(String elementId, T element) {
            this.element = element;
            this.elementId = elementId;
        }

        public static <T> Element<T> create(String elementId, T t) {
            return new Element<T>(elementId, t);
        }
    }

    private final String dir;
    private final String claimDir;

    private AtomicReference<ZooKeeper> zookeeperRef;
    private List<ACL> acl = ZooDefs.Ids.OPEN_ACL_UNSAFE;

    private static final String prefix = "qn-";

    /**
     *
     * @param zookeeperRef     zookeeper client
     * @param dir            Assumed created; directory under which jobs will be created
     * @param claimDir    Assumed created; directory under which running jobs will be added
     * @param acl            ACL list
     */
    public ZkMessageQueue(AtomicReference<ZooKeeper> zookeeperRef, String dir, String claimDir, List<ACL> acl){
        this.dir = dir;
        this.claimDir = claimDir;

        if(acl != null){
            this.acl = acl;
        }
        this.zookeeperRef = zookeeperRef;

    }

    private TreeMap<Long,String> orderedChildren(Watcher watcher) throws KeeperException, InterruptedException {
        return orderedChildrenInternal(dir, watcher);
    }

    private TreeMap<Long,String> orderedClaimedChildren(Watcher watcher) throws KeeperException, InterruptedException {
        return orderedChildrenInternal(claimDir, watcher);
    }

    /**
     * Returns a Map of the children, ordered by id.
     * @param watcher optional watcher on getChildren() operation.
     * @return map from id to child name for all children
     */
    private TreeMap<Long,String> orderedChildrenInternal(
            String directory,
            Watcher watcher) throws KeeperException, InterruptedException {
        TreeMap<Long,String> orderedChildren = new TreeMap<Long,String>();

        List<String> childNames = null;
        do {
            try{
                childNames = zookeeperRef.get().getChildren(directory, watcher);
                break;
            }catch (KeeperException.NoNodeException e){
                createDir();
            }
        } while (true);

        for(String childName : childNames){
            try{
                //Check format
                if (!childName.startsWith(prefix)) {
                    LOG.warn("Found child node with improper name: " + childName + "; expected prefix: " + prefix);
                    continue;
                }
                String suffix = childName.substring(prefix.length());
                final long childId = Long.valueOf(suffix);
                orderedChildren.put(childId,childName);
            }catch(NumberFormatException e){
                LOG.warn("Found child node with improper format : " + childName + " " + e,e);
            }
        }

        return orderedChildren;
    }

    private void createDir() throws KeeperException, InterruptedException {
        try {
            zookeeperRef.get().create(dir, new byte[0], acl, CreateMode.PERSISTENT);
            LOG.info("Created dir : " + dir + " in zk");

            zookeeperRef.get().create(claimDir, new byte[0], acl, CreateMode.PERSISTENT);
            LOG.info("Created dir : " + claimDir + " in zk");
        } catch (KeeperException.NodeExistsException e) {
            // ignore, may be a case of race condition
        }
    }

    /**
     * Get first available (that is un-claimed) element from the ordered child list. Then get the child's data from
     * zookeeper
     * @param orderedChildren
     *         List of ordered children from zookeeper
     * @param markClaimed
     *         Whether to claim this child. If true, the child will not be available in the next read until
	 *         put back by calling putbackElement()
     * @return
     *         Bytes of available element, Optional.absent() otherwise
     * @throws KeeperException
     * @throws InterruptedException
     */
    private Optional<Element<byte[]>> getFirstAvailableElement(NavigableMap<Long, String> orderedChildren,
            boolean markClaimed) throws KeeperException, InterruptedException {

        boolean hasUnClaimedElements = false;
        TreeMap<Long, String> claimedChildren = orderedClaimedChildren(null);

        for (Entry<Long, String> entry : orderedChildren.entrySet()) {
            // skip children already claimed
            if (claimedChildren.containsKey(entry.getKey())) {
                LOG.debug("Element already claimed: " + entry.getValue(), "; skipping");
                continue;
            }

            hasUnClaimedElements = true;
            final String elementId = entry.getValue();
            try {
                final String claimPath = claimDir + "/" + elementId;
                while (markClaimed) {
                    try {
                        final byte[] hostNameBytes = StringUtils.getBytesUtf8(getLocalHostName());
                        zookeeperRef.get().create(claimPath, hostNameBytes, acl, CreateMode.EPHEMERAL);
                        LOG.info("Element claimed: " + entry.getValue());
                        break;
                    } catch (KeeperException.NodeExistsException e) {
                        LOG.info("Node at path: " + claimPath + " already exists. Trying different node.");
                        throw e; // will be caught by the outer loop.
                    } catch (KeeperException.NoNodeException e) {
                        createDir();
                    }
                }

                final String path = dir + "/" + elementId;
                byte[] data = zookeeperRef.get().getData(path, false, null);
                return Optional.of(Element.create(elementId, data));
            } catch (KeeperException.NodeExistsException e) {
                // Another client claimed the node first.
                // Refresh claimed children
                claimedChildren = orderedClaimedChildren(null);
            }
        }

        if (!hasUnClaimedElements) {
            throw new NoSuchElementException("No unclaimed element found");
        }

        return Optional.absent();
    }

    /**
     * Return the head of the queue without modifying the queue.
     * @return the data at the head of the queue.
     * @throws NoSuchElementException
     * @throws KeeperException
     * @throws InterruptedException
     */
    public Element<byte[]> element() throws NoSuchElementException, KeeperException, InterruptedException {
        // element, take, and remove follow the same pattern.
        // We want to return the child node with the smallest sequence number.
        // Since other clients are remove()ing and take()ing nodes concurrently,
        // the child with the smallest sequence number in orderedChildren might be gone by the time we check.
        // We don't call getChildren again until we have tried the rest of the nodes in sequence order.
        while(true){
            final TreeMap<Long, String> orderedChildren = orderedChildren(null);
            final Optional<Element<byte[]>> element = getFirstAvailableElement(orderedChildren, false);
            if (element.isPresent())
                return element.get();
        }
    }

    /**
     * Attempts to read the head of the queue and return it.
     * @return The former head of the queue
     * @throws NoSuchElementException
     * @throws KeeperException
     * @throws InterruptedException
     */
    public Element<byte[]> dequeue() throws NoSuchElementException, KeeperException, InterruptedException {
        while(true){
            TreeMap<Long, String> orderedChildren = orderedChildren(null);
            final Optional<Element<byte[]>> element = getFirstAvailableElement(orderedChildren, true);
            if (element.isPresent())
                return element.get();
        }
    }

    public long size() throws InterruptedException, KeeperException {
        final Stat stats = zookeeperRef.get().exists(dir, false);
        return (stats == null? 0 : stats.getNumChildren());
    }

    private static class LatchChildWatcher implements Watcher {
        CountDownLatch latch;

        public LatchChildWatcher(){
            latch = new CountDownLatch(1);
        }

        public void process(WatchedEvent event){
            LOG.debug("Watcher fired on path: " + event.getPath() + " state: " +
                    event.getState() + " type " + event.getType());
            latch.countDown();
        }
        public void await() throws InterruptedException {
            latch.await();
        }
    }

    /**
     * Removes the head of the queue and returns it, blocks until it succeeds.
     * @return The former head of the queue
     * @throws NoSuchElementException
     * @throws KeeperException
     * @throws InterruptedException
     */
    public Element<byte[]> take() throws KeeperException, InterruptedException {
        // Same as for element.  Should refactor this.
        while(true){
            final TreeMap<Long, String> orderedChildren;
            final LatchChildWatcher childWatcher = new LatchChildWatcher();
            try{
                orderedChildren = orderedChildren(childWatcher);
                final Optional<Element<byte[]>> element = getFirstAvailableElement(orderedChildren, true);
                if (element.isPresent())
                    return element.get();
            } catch (NoSuchElementException e) {
                childWatcher.await();
            }
        }
    }

    /**
     * Inserts data into queue.
     * @param data
     * @return true if data was successfully added
     */
    public boolean offer(byte[] data) throws KeeperException, InterruptedException{
        for(;;){
            try{
                zookeeperRef.get().create(dir + "/" + prefix, data, acl, CreateMode.PERSISTENT_SEQUENTIAL);
                return true;
            }catch(KeeperException.NoNodeException e){
                createDir();
            }
        }

    }

    /**
     * Returns the data at the first element of the queue, or null if the queue is empty.
     * @return data at the first element of the queue, or null.
     * @throws KeeperException
     * @throws InterruptedException
     */
    public Element<byte[]> peek() throws KeeperException, InterruptedException {
        try{
            return element();
        }catch(NoSuchElementException e){
            return null;
        }
    }

    public void removeElement(String elementId) throws KeeperException, InterruptedException {
        deletePath(dir + "/" + elementId);
        deletePath(claimDir + "/" + elementId);
    }

    public void putbackElement(String elementId) throws KeeperException, InterruptedException {
        deletePath(claimDir + "/" + elementId);
    }

    private void deletePath(String path) throws KeeperException, InterruptedException {
        try {
            zookeeperRef.get().delete(path, -1);
        } catch (KeeperException e) {
            LOG.error("Exception while deleting the element at path: " + path, e);
            throw e;
        }
    }

    /**
     * Attempts to remove the head of the queue and return it. Returns null if the queue is empty.
     * @return Head of the queue or null.
     * @throws KeeperException
     * @throws InterruptedException
     */
     public Element<byte[]> poll() throws KeeperException, InterruptedException {
        try{
            return dequeue();
        }catch(NoSuchElementException e){
            return null;
        }
    }

    public Element<byte[]> inspect(String elementId) throws KeeperException, InterruptedException {
        try {
            final String path = dir + "/" + elementId;
            final byte[] data = zookeeperRef.get().getData(path, false, null);
            return Element.create(elementId, data);
        } catch (KeeperException.NoNodeException e) {
            throw new NoSuchElementException("Not found: " + elementId + " at " + dir);
        }
    }

    private static String getLocalHostName() {
        try {
            return InetAddress.getLocalHost().getHostName();
        } catch (UnknownHostException e) {
            return "localhost";
        }
    }
}
