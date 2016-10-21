#! /usr/bin/env python

import cgi, MySQLdb

print('Content-type: text/html\n')

form = cgi.FieldStorage()
id = form.getfirst('id','1211')

string = "i211u16_pekirby" 	
password = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=password, db=string)

cursor = db_con.cursor()


html = """<!DOCTYPE html>
<html>
<body>
<h1>Accounts Deleted!</h1>
{content}
<a href = "http://cgi.soic.indiana.edu/~pekirby/show_account.cgi">Go Back</a>
</body>
</html>"""



def delete():
	try:
		sql = "delete from Accounts where Id = '"+id+"';"
		cursor.execute(sql)
		db_con.commit()
	except Exception, e:
		print('<p>Something went wrong with the SQL!</p>')
        print(sql, "\nError:", e)
	else:
		print(html.format(content = 'The row was successfully deleted!'))
		
delete()		
