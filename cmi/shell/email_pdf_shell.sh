
#!/bin/bash
YEAR=`date -d "1 day ago" '+%Y'`
MONTH=`date -d "1 day ago" '+%m'`
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 01
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 02
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 03
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 04
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 05
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 06
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 07
/usr/bin/php /var/www/html/cmi/shell/email_pdf_shell.php $YEAR $MONTH 08
