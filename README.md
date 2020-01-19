# asciinema-upload
Basic Asciinema Upload endpoint written in pure php

set your ASCIINEMA_API_URL=http://127.0.0.1/upload.php asciinema rec -i 1 -y

create a folder uploads with write permissions for www-data

make sure upload_tmp_dir is set in php.ini

the upload.php logs exessively in apache error.log you might want to remove some lines.

don't name the file index.php and use the topfolder as api endpoint, $_FILES would be empty.
