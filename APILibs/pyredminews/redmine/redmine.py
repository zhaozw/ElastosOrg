# PyRedmineWS - Python Redmine Web Services
#
#    This program is free software: you can redistribute it and/or modify
#    it under the terms of the GNU General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    This program is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU General Public License for more details.
#
#    You should have received a copy of the GNU General Public License
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.

from redmine_rest import Redmine_Item, Redmine_Items_Manager, Redmine_WS
from redmine_rest import RedmineError

# To create a new item to be tracked from Redmine, create a class for that item
# based on the Redmine_Item class.  The class name must be identical to the name
# of that item within the rest interfaces (case insensative).  For instance,
# Redmine will return an item called "time_entry", and the module to interface
# with that item must be called Time_Entry.

# The class should provide hints as to the data that will be returned by
# setting the expected item to None.

# The class should indicate which of those fields are not user modifiable
# by setting the _protexted_attr list.  For instance, the 'id' cannot
# be modified, so it should be places in the list to throw an error 
# immediately when the user attempts to set the value, instead of 
# having an (occasionally silent) failure when the user tries to save
# their data.  Other fields that are often not user modifiable might be
# 'created_on', 'last_login', etc.
# By default, the only protected item is 'id'.

# Within the Redmine REST interface, some items are retrieved via one
# parameter, but set by another.  For instance, when getting the 
# project from an issue, that info will come from the 'project' field,
# however if you want to set the project for the issue, we must set the
# project_id field.  In order to properly handle this, the Redmine_Item
# base class looks for a list called _remap_to_id and will convert
# those fields to their equivalent _id fields and remap the data accordingly.
# For instance, issues get a 'category' with an ID and a name, but when the
# user changes the category and sets it to a new value (either the numeric ID,
# a dictionary containing an 'id' key or an object with an 'id' attribute)
# the id will be extracted and sent via the 'category_id' field.

# In order to get data from and set data to the server, the Redmine_Items_Manager
# looks for specific fields within the item class to tell it which path to 
# use.  Also, information coming back from queries is contained within
# a wrapper, which is usually, but not always, just the plural of the item name.
# (project -> projects, user -> users, time_entry -> time_entries, news -> news)
# If any of the information isn't provided, the equivalent functions will
# not be available.
#
#	_query_container = 'items'      # Usually the plural of the 'item'.
#	_query_path = '/items.json'     # Used for generating lists of items.
#                                   # (for item in redmine.items(query_options):... )
#	_item_path = '/items/%s.json'   # Where to get the individual item and save it back.
#	_item_new_path = '/items.json'  # Where to put new item info.  
#                                   # Often the same as the _query_path.

# By default, the __str__ just returns the item's name.  If there's a better representation
# of the item, then it's a good idea to override this and provide it.
# __int__ will provide the ID.



class Project(Redmine_Item):
	'''Object representing a Redmine project.
	'''
	# data hints:
	id = None
	name = None
	identifier = None
	parent = None
	homepage = None
	created_on = None
	updated_on = None
	
	_protected_attr = ['id',
					   'created_on',
					   'updated_on',
					   'identifier',
					   ]

	_field_type = {
		'parent':'project', 
		'assigned_to':'user',
		'author':'user',
		'created_on':'datetime',
		'updated_on':'datetime',
		}
	
	# How to communicate this info to/from the server
	_query_container = 'projects'
	_query_path = '/projects.json'
	_item_path = '/projects/%s.json'
	_item_new_path = '/projects.json'

	def __init__(self, redmine, *args, **kw_args):
		# Override init to also set up sub-queries
		# Call the base-class init
		super(Project, self).__init__(redmine, *args, **kw_args)
		
		# Manage issues for this project,
		# using __dict__ to bypass the changes__dict__
		# Bake this project ID into queries and new issue commands
		self.__dict__['issues'] = Redmine_Items_Manager(redmine, Issue,
													query_path='/projects/%s/issues.json' % self.id,
													item_new_path='/projects/%s/issues.json' % self.id )
		
		# Manage time entries for this project
		self.__dict__['time_entries'] = Redmine_Items_Manager(redmine, Time_Entry,
													query_path='/projects/%s/time_entries.json' % self.id,
													item_new_path='/projects/%s/time_entries.json' % self.id)

		
		# Manage wiki pages if they're available
		if redmine._wiki_pages:
			self.__dict__['wiki_pages'] = Redmine_Wiki_Pages_Manager(redmine, self)
			
	def __repr__(self):
		return '<Redmine project #%s "%s">' % (self.id, self.identifier)


class Issue(Redmine_Item):
	'''Object representing a Redmine issue.'''
	# data hints:
	id = None
	subject = None
	description = None
	tracker = None
	status = None
	project = None
	estimated_hours = None
	done_ratio = None
	assigned_to = None
	start_date = None
	due_date = None
	
	_protected_attr = ['id',
					   'created_on',
					   'updated_on',
					   'identifier',
					   ]

	_field_type = {
		'parent':'issue',
		'assigned_to':'user',
		'author':'user',
		'created_on':'datetime',
		'updated_on':'datetime',
		'start_date':'date',
		'due_date':'date',
		}


	# these fields will map from tag to tag_id when saving the issue.
	# for instance, redmine needs the category_id=#, not the category as given
	# the logic will attempt to grab category['id'] to set category_id 
	_remap_to_id = ['assigned_to',
				    'project',
				    'category',
				    'status',
				    'parent_issue']

	# How to communicate this info to/from the server
	_query_container = 'issues'
	_query_path = '/issues.json'
	_item_path = '/issues/%s.json'
	_item_new_path = '/issues.json'


	def __init__(self, redmine, *args, **kw_args):
		# Override init to also set up sub-queries
		# Call the base-class init
		super(Issue, self).__init__(redmine, *args, **kw_args)
		
		# to manage time_entries for this issue
		self.__dict__['time_entries'] = Redmine_Items_Manager(redmine, Time_Entry)
		self.time_entries._query_path = '/issues/%s/time_entries.json' % self.id
		self.time_entries._item_new_path = self.time_entries._query_path

	def __str__(self):
		return '<Redmine issue #%s, "%s">' % (self.id, self.subject)

	def save(self, notes=None):
		'''Save all changes back to Redmine with optional notes.'''
		# Capture the notes if given
		if notes:
			self._changes['notes'] = notes
		
		# Call the base-class save function
		super(Issue, self).save()

	def set_status(self, new_status, notes=None):
		'''Save all changes and set to the given new_status'''
		self.status_id = new_status
		try:
			self.status['id'] = self.status_id
			# We don't have the id to name mapping, so blank the name
			self.status['name'] = None
		except:
			pass
		self.save(notes)		

	def resolve(self, notes=None):
		'''Save all changes and resolve this issue'''
		self.set_status( self._redmine.ISSUE_STATUS_ID_RESOLVED, notes=notes )
		
	def close(self, notes=None):
		'''Save all changes and close this issue'''
		self.set_status( self._redmine.ISSUE_STATUS_ID_CLOSED, notes=notes )



class User(Redmine_Item):
	'''Object for representing a single Redmine user.'''
	# data hints:
	id = None
	login = None
	firstname = None
	lastname = None
	mail = None
	auth_source_id = None
	created_on = None
	last_login = None

	_protected_attr = ['id',
					   'created_on',
					   'last_login',
					   ]

	_field_type = {
		'created_on':'datetime',
		'last_login':'datetime',
		}
	
	# How to communicate this info to/from the server
	_query_container = 'users'
	_query_path = '/users.json'
	_item_path = '/users/%s.json'
	_item_new_path = '/users.json'

	def __str__(self):
		return '<Redmine user #%s, "%s %s">' % (self.id, self.firstname, self.lastname)


class News(Redmine_Item):
	'''Object for representing a single Redmine news story.'''
	# data hints:
	id = None
	project = None
	author = None
	title = None
	summary = None
	description = None
	created_on = None

	_protected_attr = ['id',
					   'created_on',
					   ]
	_field_type = {
		'author':'user',
		'created_on':'datetime',
		}


	# How to communicate this info to/from the server
	_query_container = 'news'
	_query_path = '/news.json'
	
	# Maybe someday...
	#_item_path = '/news/%s.json'
	#_item_new_path = '/newss.json'

	def __str__(self):
		return '<Redmine news #%s, %r>' % (self.id, self.title)


class Time_Entry(Redmine_Item):
	'''Object for representing a single Redmine time entry.'''
	# data hints:
	id = None
	project = None
	issue = None
	user = None
	activity = None
	hours = None
	comments = None
	spent_on = None
	updated_on = None
	created_on = None

	_protected_attr = ['id',
					   'created_on',
					   'updated_on',
					   ]

	_field_type = {
		'activity':'time_entry_activity',
		'created_on':'datetime',
		'updated_on':'datetime',
		'spent_on':'date',
		}

	# these fields will map from tag to tag_id when saving the time entry.
	# for instance, redmine needs the issue_id=#, not the issue as given
	# the logic will attempt to grab issue.id or issue['id'] to set issue_id 
	_remap_to_id = ['issue',
				    'project',
				    'activity',
				    'user']
	
	# How to communicate this info to/from the server
	_query_container = 'time_entries'
	_query_path = '/time_entries.json'
	_item_path = '/time_entries/%s.json'
	_item_new_path = '/time_entries.json'

	def __str__(self):
		try:
			try:
				issue = ' issue #%s' % self.issue['id']
			except:
				issue = ''
			try:
				project = ' "%s"' % self.project['name']
			except:
				project = ''
			map = (self.id, self.user['name'], project, issue, self.hours)
			return '<Redmine Time Entry #%s: "%s" worked on%s%s for %s hours>' % map
		       
		except:
			return self.__repr__()

class Time_Entry_Activity(Redmine_Item):
	'''Object for representing a single time entry activity type.
	Used for creating Time_Entries.'''
	# data hints:
	id = None
	name = None
	is_default = None

	# How to communicate this info to/from the server
	_query_container = 'time_entry_activities'
	_query_path = '/enumerations/time_entry_activities.json'


class Wiki_Page(Redmine_Item):
	'''Object for representing a single Redmine Wiki Page'''
	# data hints:
	id = None # there is no real ID. Ugh! We fake it.
	title = None
	version = None
	author = None
	comments = None
	created_on = None
	updated_on = None
	parent = None
	
	_protected_attr = ['id',
					   'created_on',
					   'updated_on',
					   'project',
					   ]

	_field_type = {
		'author':'user',
		'created_on':'datetime',
		'updated_on':'datetime',
		#'parent':'wiki_page',  Because of the lack of a numeric ID, this is an incomplete reference
		}

	# these fields will map from tag to tag_id when saving the time entry.
	# for instance, redmine needs the issue_id=#, not the issue as given
	# the logic will attempt to grab issue.id or issue['id'] to set issue_id 
	_remap_to_id = ['author']
	
	# How to communicate this info to/from the server
	# Path is /projects/<<<foo>>>/wiki/<<<page_name>>>.json.
	# ID will be passed in as <<<foo>>>/wiki/<<<page_name>>>
	_item_path = '/projects/%s.json'
	_item_new_path = '/projects/%s.json'
	# There is a query at index.json, but we'll have to override a few methods to get it working
	# because it doesn't contain real IDs, just titles and other meta-data.
	#_query_container = 'wiki_pages'
	#_query_path = '/project/<<<foo>>>/wiki.json'
	
	# We need a custom update path.  It will be passed the ID (faked, above)
	# which needs to include the project info in order to be complete.
	_update_path = '/projects/%s.json'

	def __str__(self):
		return '<Redmine wiki page %s:%s>' % (self.title, self.version)

	# No numeric ID, don't return an int representation
	def __int__(self):
		raise ValueError('Wiki page has no numeric id')


# Need a special handler for wiki pages to fake their ID based on the path
class Redmine_Wiki_Pages_Manager(Redmine_Items_Manager):
	
	def __init__(self, redmine, project):
		# Call the base class constructor
		Redmine_Items_Manager.__init__(self, redmine, Wiki_Page,
													item_path='/projects/%s/wiki/%%s.json' % project.id,
													item_new_path='/projects/%s/wiki/%%s.json' % project.id)
		self._project = project
	
	def _objectify(self, json_data=None, data={}):
		'''Return an object derived from the given json data.'''
		if json_data:
			# Parse the data
			try:
				data = json.loads(json_data)
			except ValueError:
				# If parsing failed, then raise the string which likely contains an error message instead of data
				raise RedmineError(json_data)            
		# Check to see if there is a data wrapper
		# Some replies will have {'issue':{<data>}} instead of just {<data>}
		try:
			data = data[self._item_type]
		except KeyError:
			pass
		
		# If there's no ID but a source path
		if ( not data.has_key('id') ) and data.has_key('_source_path'):
			# use the path between /projects/ and .json as the ID
			data['id'] = data['_source_path'].partition('/projects/')[2].partition('.json')[0]
		
		# Call the base class objectify method
		return super(Redmine_Wiki_Pages_Manager, self)._objectify(data=data)
		#return Redmine_Items_Manager._objectify(self, data=data)

	def new(self, page_name, **dict):
		'''Create a new item with the provided dict information at the given page_name.  Returns the new item.
		Unfortunately, as of version 2.2 of Redmine, this doesn't seem to function.'''
		self._item_new_path = '/projects/%s/wiki/%s.json' % (self._project.identifier, page_name)
		# Call the base class new method
		return super(Redmine_Wiki_Pages_Manager, self).new(**dict)
		
		

class Redmine(Redmine_WS):
	'''Class to interoperate with a Redmine installation using the REST web services.
	instance = Redmine(url, [key=<string>], [username=<string>, password=<string>], [version=<#.#>], [impersonate=<string>] )
	
	url is the base url of the Redmine install ( http://my.server/redmine )
	
	key is the user API key found on the My Account page for the logged in user
		All interactions will take place as if that user were performing them, and only
		data that that user can see will be seen

	If a key is not defined then a username and password can be used
	If neither are defined, then only publicly visible items will be retreived
	
	If impersonate is set and the logged in user has administrator privileges,
	the user will be switched.

	When the version parameter is set, only items available in that version of Redmine
	are enabled.  For instance, version 1.0 only supports issue and project management,
	but issue 1.1 adds users, news and time entries and more secure key handling.  If
	the version is left off, more features and less security will be enabled for the 
	best chance of a fully functioning module but the errors for attempting to use 
	an unsupported function may be less than intuitive. 
	
	Depending on the version of Redmine, the following item managers may be available:
	issues
	projects
	
	Redmine version 1.1 adds:
	users
	news
	time_entries
	
	Redmine version 2.2 adds:
	time_entry_activities
	'''
	# Status ID from a default install
	ISSUE_STATUS_ID_NEW = 1
	ISSUE_STATUS_ID_RESOLVED = 3
	ISSUE_STATUS_ID_CLOSED = 5		
	
	_current_user = None
	
	@property
	def user(self):
		if not self._current_user:
			self._current_user = self.users['current']
		return self._current_user
	
	def _set_version(self, version):
		'''Set up this object based on the capabilities of the known versions of Redmine'''
		# Store the version we are evaluating
		self.version = version or None
		# To evaluate the version capabilities, assume the best-case if no version is provided
		version_check = version or 9999.0

		if version_check < 1.0:
			raise RedmineError('This library will only work with Redmine version 1.0 and higher.')
		
		## SECURITY AUGMENTATION		
		# All versions support the key in the request (http://server/stuff.json?key=blah)
		# But versions 1.1 and higher can put the key in a header field for better security.
		# If no version was provided (0.0) then assume we should set the key with the request.
		if version >= 1.1:
			self.key_in_header = True  # it puts the key in the header or it gets the hose, but not for 1.0
		
		self.impersonation_supported = version_check >= 2.2

		## ITEM MANAGERS
		self.issues = Redmine_Items_Manager(self, Issue)
		self.projects = Redmine_Items_Manager(self, Project)

		if version_check >= 1.1:
			self.users = Redmine_Items_Manager(self, User)
			self.news = Redmine_Items_Manager(self, News)
			self.time_entries = Redmine_Items_Manager(self, Time_Entry)
		
		if version_check >= 1.3:
			#issue relations
			#versions
			#queries
			#attachments
			#issue statuses
			#trackers
			#issue categories
			pass
		
		if version_check >= 1.4:
			#project memberships
			#roles
			pass
			
		if version_check >= 2.1:
			#groups
			pass
		
		self._wiki_pages = False
		if version_check >= 2.2:
			self.time_entry_activities = Redmine_Items_Manager(self, Time_Entry_Activity)
			self._wiki_pages = True
			#enumerations
				
		

