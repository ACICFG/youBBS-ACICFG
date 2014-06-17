YouBBS-ACICFG
=====================
YouBBS-ACICFG  
ver 1.04.05

This is a simple and quick BBS.

Looks like V2EX, but in PHP.

Smaller than StartBBS(Openshift quick install ver. avalable in my Github:-) ).


We made some improvements:
----------------

1. Set a post at top(finally! After 180+ days of waiting!)

2. Make it easier for admins to delete or edit posts.

3. Allow users to edit their posts. (.05: As well as comments)

4. Markdown support, with a nice looking user-friendly editor.

5. Auto-save support.

6. Set post as red.

7. Restrict refresh to prevent CC attack.

And more...

Install:
------------

The same as the original one.

0. This version DO NOT SUPPORT Memcache, and REQUIRE Rewrite.
1. Create a new user and database with MySQL. MariaDB would also do.
2. Edit the .conf file for Nginx, or use the .htaccess for Apache.(Apache version is automatic converted, no gurantee of effect)
3. Edit the config.php, put in your database information.
4. Run http://get.your.website.com/install to update the database.
5. Now you should be able to use the programme. The first register user would automaticly become System Admin.

Special notes to nginx users
------------
To help you fix the annoying rewrite, we put a readme-nginx.txt in it.

Just copy and paste them in the .conf file.


Name
----------
1.04                 .05
1.04                 .02
Original ver.        Our update


Credits
-----

Official BBS:http://youbbs.sinaapp.com/

Proudly served by ACICFG Tech Team.

Our official website: https://forum.chineseaci.com/

For help. get in touch with us at http://www.chineseaci.com.

Special thanks to those who contributed in this project. You guys made a difference.

Hope you like it :-)


Beining(@cnbeining ), along with @sundaymouse , zhxq
Tech Team of ACICFG
Jun.17.2014
