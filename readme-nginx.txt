rewrite ^/n-([0-9]+)(-([0-9]*))?$ /nodepage.php?cid=$1&page=$3 last;
rewrite ^/t-([0-9]+)(-([0-9]*))?$ /topicpage.php?tid=$1&page=$3 last;
rewrite ^/notifications$ /notifications.php last;
rewrite ^/favorites$ /favorites.php last;
rewrite ^/qqlogin$ /qqlogin.php last;
rewrite ^/qqcallback$ /qqcallback.php last;
rewrite ^/qqsetname$ /qqsetname.php last;
rewrite ^/feed$ /feed.php last;
rewrite ^/robots$ /robots.php last;
rewrite ^/forgot$ /forgot.php last;
rewrite ^/sitemap-([0-9]+)$ /sitemap.php?id=$1 last;
rewrite ^/upload-(650|590)$ /upload.php?mw=$1 last;
rewrite ^/viewat-(desktop|mobile)$ /viewat.php?via=$1 last;
rewrite ^/goto-t-([0-9]+)$ /gototopic.php?tid=$1 last;
rewrite ^/member/(.+)$ /member.php?mid=$1 last;
rewrite ^/newpost/([0-9]+)$ /newpost.php?cid=$1 last;
rewrite ^/admin-edit-post-([0-9]+)$ /admin-edit-post.php?tid=$1 last;
rewrite ^/admin-edit-comment-([0-9]+)$ /admin-edit-comment.php?rid=$1 last;
rewrite ^/admin-setuser-([0-9]+)$ /admin-setuser.php?mid=$1 last;
rewrite ^/admin-node(-([0-9]*))?$ /admin-node.php?nid=$2 last;
rewrite ^/admin-setting$ /admin-setting.php last;
rewrite ^/admin-user-([a-z]+)(-([0-9]*))?$ /admin-user.php?act=$1&mid=$3 last;
rewrite ^/admin-link-([a-z]+)(-([0-9]*))?$ /admin-link.php?act=$1&lid=$3 last;
rewrite ^/(login|sigin|logout|forgot|setting|install)$ /$1.php last;
rewrite ^/.*?templates /404.html last;
rewrite ^/.*?avatar/$ /404.html last;
rewrite ^/upload/([0-9]+/)?$ /404.html last;
rewrite ^/.*?avatar/(large|normal|mini)/$ /404.html last;



rewrite ^/user-edit-post-([0-9]+)$ /user-edit-post.php?tid=$1 last;