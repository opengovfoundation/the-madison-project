**Note: This is the legacy version of Madison.  It is currently being developed using [Laravel](http://three.laravel.com) at [the new project page](https://github.com/opengovfoundation/madison)**

# Madison
***

## The Problem Domain

As the governments have grown in power, secretiveness and size, the ability for individuals to know what government does, to contribute their knowledge to government policymaking, and to hold government accountable has remained weak at best.  But as more people access the Internet and use technology to make informed decisions in their personal and professional lives, there is a need for the same peer-to-peer information-sharing and collaboration tools in the relationship between citizens and their government.  Madison will deliver that open collaboration between citizens, organizations and their governments.

## A Solution: Madison

Madison is a public document editing tool that supports user commenting, sharing and collaboration.  Launched to battle the Stop Online Piracy Act (SOPA), it has since been used to power citizen participation in the crowd-sourced development and improvement of official government documents in the United States Congress.  Right now, a working version of Madison lives at [www.KeepTheWebOPEN.com](http://www.keepthewebopen.com) and hosts a range of collaborative projects and conversations around official federal government documents - examples include the leaked intellectual property chapter of the [Trans Pacific Partnership](http://keepthewebopen.com/tpp), [a Digital Citizen's Bill of Rights](http://www.keepthewebopen.com/digital-bill-of-rights), [the Anti Counterfeiting Trade Agreement](http://www.keepthewebopen.com/acta) and an alternative to [SOPA](http://www.keepthewebopen.com/sopa) called the [OPEN Act](http://www.keepthewebopen.com/open).

The goal of this development project is to deliver software that supports open, accountable, social and collaborative government.  Specifications include but are not limited to the following: It needs to allow users – individuals and organizations - to comment on, edit/improve and share discreet chunks of government documents. It needs to power discussion around those chunks, including comments, sharing, and debate. It needs to allow users to draft from scratch and publicly display that user’s prior contributions and discussion. Users should be able to load “drafts” from other users as starting points for their own work. On the backend, the document’s sponsor (e.g. a Congressman, City Councilwoman, or advocacy group) needs to be able to make sense of the contributions, separate the wheat from the chaff, and be able to seamlessly respond to and incorporate user-generated comments and suggestions.

*Every new, “living” government document needs to reference the parts of
the law/regulations/treaties that it changes, the user needs to be able
to view the “living” document within the referenced document (e.g.
individual document view vs. US Code view), and the user-generated data
needs to be associated with both.*

### Installation Documentation
Installation documentation can be found here: [Installation Documentation](INSTALL.md)	

#### Overview
When a person visits Madison as it stands now, they are presented with a document, a side bar of document edits and comments made by other users, and login and create an account options.  Individuals may create a user name and password for themselves, request authentication for an organization user name and password, or login with Facebook.  Vistors may read and share the document, as well as registered users edits and comments, without logging into Madison.  

#### Document Interaction
The documents are delimited into sections, which show the number of comments and suggestions made on that section in the default view.  The Community Suggestions box displays proposed bill edits and the Community Comments box displays section comments that do not propose an edit.  The user-generated comments and suggestions receiving the most social activity and discussion appear at the top of the default sidebar.  When a visitor clicks on a section of the document, it becomes highlighted and the sidebar populates with the user-generated comments and suggestions for that particular document section.  The visitor can click on each edit and comment to see the user who made it, when they made it, how much social activity it has received (likes, dislikes, Facebook shares and Tweets), the full text of the edit and the discussion that has happened around that edit or comment.  Once a visitor logs in - becoming a user - options to make bill edits and comments appear on the side bar when the user clicks on a section of the document.  The user can select one of those options to edit or comment on the document. 

### Current Status

* The documents are currently input manually into the database with each editable section broken into a different row
	* The document layout is handled by manually entering each section's parent id
	* The document title and slug are also input manually into the database
	* The document description and navigation items are added manually to the html
	* The twitter tweet button inputs are added manually to the html

* There are separate account types for general users and organizations	
*Organizations can apply to post as the organization itself, but must be manually approved beforehand.  A user with administrative privileges can login and go to /company-approval to moderate these requests.*

* The users can currently submit two kinds of notes - comments and suggestions
	* All notes can be liked, disliked, or flagged as inappropriate
	* 'Suggestions' are document edits submitted by users
	* 'Comments' are general user comments on a section without any proposed edits
	
* Facebook Connect is currently integrated to allow users to log in with their Facebook accounts  
*If the user does not have a local account, one is created when they log in via Facebook for the first time*


### Roadmap
While this is not an exhaustive list of technical considerations, this overview will give insight into the overall implementation of and functionality of Madison:

* There will be an install script that runs on new Madison clones that will do the following:
	* Set up the database tables
	* Create an administrative user
	* Ask the user whether he/she wants to register with Madison  
*This will allow Madison to create a community of clone installs that it can ping for community content*
	
* Registering with Madison will allow the parent Madison install to keep up to date with the Madison community.  The default sharing settings on each install will be to not share content, with the ability to opt-in.  
	* The administrative user will be able to opt-in for clone registration
	* The administrative user will be able to opt-in for each document or a general opt-in for all documents
	* Users will be able to opt-in, which will also create a user account on the parent Madison install and sync their user data
	* Registered Madison clones will be able to import documents from the parent Madison install  
*There should be document import features for government documents such as importing documents from thomas.house.gov*

* Adding documents to Madison is currently a long process due to manually interacting with the database.  To make document creation easier, we will need to add
	* Document import functionality  
*Initially this will import delimited documents, but could expand to parsing OpenOffice documents*
	* Drag & drop document editing (similar to Wordpress's menu/submenu drag and drop interface)

* The ability to view a document in its larger
context will create a network-like structure for the documents. The physical
size of this database will be extremely large by including the documents
themselves, historic context and legislation, meta tables, comment tables, etc.

* This application has a very high amount of browser-side functionality and
a focus on real-time interactivity. This will make scaling the application
difficult due to caching processes, AJAX requests, etc. We also will want to
live-stream certain events to users as they pertain to particular documents.

* We will need a system to allow the users to separate the legitimate content from trolls or spammers by utilizing some or all of the techniques below:
   	* Create slug-specific field names as MD5 hashes that must be submitted correctly in combination for the submission to be accepted
	* Include fields hidden via CSS that would be invisible to the users and would be used to discard spambot submissions
	* Create the pages with incorrect form actions, and change via js on form submission
	* Inspect the origin of the submission to filter out foreign submissions
	* Akismet could be used to filter bad submissions
	* The system to filter better content to the top will user social media interaction and possible site analytics.

Initially, users will be able to automatically log in with their Facebook account.  We plan to incorporate similar functionality for Twitter, Google+, reddit and other social media platforms as demand guides us.  Initially, users will be able to share discussions, edits and comments - their own and other users' - on Twitter and Facebook and we plan to incorporate similar functionality for Google+, reddit and other implemented social media platforms as demand guides us.

The UI is undetermined, but we are looking at existing systems for guidance.  Examples include: YouVersion, OpenCongress, Document Cloud, Wikipedia and reddit.
	
### Technical Details
For information on the technical details of Madison, please visit the [Technical Documentation](DETAILS.md)	
	
### Questions and Feedback
Please send all questions and feedback to sayhello {AT} opengovfoundation.org or join the project's [Google Group](https://groups.google.com/forum/#!forum/opensourcemadison)

### Bug Tracker
We are using the built-in gitHub bug tracker for this project. 

### Acknowledgements
We'd like to thank Matt Turow, Jayson Manship, Chris Birk, Brooke McKinney, Caleb D., and Jeremiah Reising of [inSourceCode, LLC](http://www.insourcecode.com) for turning Madison from an idea into something real, and for their continued support and involvement in the Madison Project.

We'd like to thank Karl Fogel of [Open Tech Strategies, LLC](http://opentechstrategies.com) for volunteering his time and expertise in preparing Madison for posting on GitHub.

***
### License

The Madison Project: open source software to power open collaboration, editing and sharing of public documents.

Copyright (C) 2012 by The OpenGov Foundation

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <http://www.gnu.org/licenses/>.

The OpenGov Foundation
PO Box 3672
Washington, DC 20027
Email: sayhello {at} opengovfoundation.org