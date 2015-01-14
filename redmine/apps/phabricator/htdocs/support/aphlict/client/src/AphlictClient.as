package {

  import flash.events.TimerEvent;
  import flash.external.ExternalInterface;
  import flash.utils.Dictionary;
  import flash.utils.Timer;
  import flash.events.UncaughtErrorEvent;

  final public class AphlictClient extends Aphlict {

    /**
     * The connection name for this client. This will be used for the
     * @{class:LocalConnection} object.
     */
    private var client:String;

    /**
     * The expiry timestamp for the @{class:AphlictMaster}. If this time is
     * elapsed then the master will be assumed to be dead and another
     * @{class:AphlictClient} will create a master.
     */
    private var expiry:Number = 0;

    /**
     * The interval at which to ping the @{class:AphlictMaster}.
     */
    public static const INTERVAL:Number = 3000;

    private var master:AphlictMaster;
    private var timer:Timer;

    private var remoteServer:String;
    private var remotePort:Number;
    private var subscriptions:Array;


    public function AphlictClient() {
      super();

      loaderInfo.uncaughtErrorEvents.addEventListener(
        UncaughtErrorEvent.UNCAUGHT_ERROR,
        this.uncaughtErrorHandler);

      ExternalInterface.marshallExceptions = true;
      ExternalInterface.addCallback('connect', this.externalConnect);

      this.setStatus('ready');
    }

    private function uncaughtErrorHandler(event:UncaughtErrorEvent):void {
      this.error(event.error.toString());
    }

    public function externalConnect(
      server:String,
      port:Number,
      subscriptions:Array):void {

      this.remoteServer  = server;
      this.remotePort    = port;
      this.subscriptions = subscriptions;

      this.client = AphlictClient.generateClientId();
      this.recv.connect(this.client);

      this.timer = new Timer(AphlictClient.INTERVAL);
      this.timer.addEventListener(TimerEvent.TIMER, this.keepalive);

      this.connectToMaster();
    }

    /**
     * Generate a unique identifier that will be used to communicate with the
     * @{class:AphlictMaster}.
     */
    private static function generateClientId():String {
      return 'aphlict_client_' + Math.round(Math.random() * 100000);
    }

    /**
     * Create a new connection to the @{class:AphlictMaster}.
     *
     * If there is no current @{class:AphlictMaster} instance, then a new master
     * will be created.
     */
    private function connectToMaster():void {
      this.timer.stop();

      // Try to become the master.
      try {
        this.log('Attempting to become the master...');
        this.master = new AphlictMaster(this.remoteServer, this.remotePort);
        this.log('I am the master.');
      } catch (err:ArgumentError) {
        this.log('Cannot become the master... probably one already exists');
      } catch (err:Error) {
        this.error(err);
      }

      this.registerWithMaster();
      this.timer.start();
    }

    /**
     * Register our client ID with the @{class:AphlictMaster} and send our
     * subscriptions.
     */
    private function registerWithMaster():void {
      this.send.send('aphlict_master', 'register', this.client);
      this.expiry = new Date().getTime() + (5 * AphlictClient.INTERVAL);
      this.log('Registered client ' + this.client);

      // Send subscriptions to master.
      this.log('Sending subscriptions to master.');
      this.send.send(
        'aphlict_master',
        'subscribe',
        this.client,
        this.subscriptions);
    }

    /**
     * Send a keepalive signal to the @{class:AphlictMaster}.
     *
     * If the connection to the master has expired (because the master has not
     * sent a heartbeat signal), then a new connection to master will be
     * created.
     */
    private function keepalive(event:TimerEvent):void {
      if (new Date().getTime() > this.expiry) {
        this.connectToMaster();
      }

      this.send.send('aphlict_master', 'ping', this.client);
    }

    /**
     * This function is used to receive the heartbeat signal from the
     * @{class:AphlictMaster}.
     */
    public function pong():void {
      this.expiry = new Date().getTime() + (2 * AphlictClient.INTERVAL);
    }

    /**
     * Receive a message from the Aphlict Server, via the
     * @{class:AphlictMaster}.
     */
    public function receiveMessage(msg:Object):void {
      this.log('Received message.');
      this.externalInvoke('receive', msg);
    }

    public function setStatus(status:String, code:String = null):void {
      this.externalInvoke('status', {type: status, code: code});
    }

  }

}
