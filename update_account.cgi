#! /usr/bin/env python

import cgi, MySQLdb

print('Content-type: text/html\n')

form = cgi.FieldStorage()

id = form.getfirst('id','')
username = form.getfirst('user','')
password = form.getfirst('password','')
first_name = form.getfirst('fname','')
last_name = form.getfirst('lname','')
birthday = form.getfirst('bday','')
address = form.getfirst('address','')
phone = form.getfirst('phone','')
picture = form.getfirst('picture','')


string = "i211u16_pekirby" 	
pword = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=pword, db=string)

cursor = db_con.cursor()




html = """<!DOCTYPE html>
<html>
<body>
<h1>Account Changes Saved!</h1>
<p><a href='show_account.cgi'>Go Back</a></p>
</body>
</html>"""



def update():
	try:
		sql = "update Accounts set Username = '"+username+"', Password = '"+password+"', FirstName = '"+first_name+"', LastName = '"+last_name+"',Birthday = '"+birthday+"',Address = '"+address+"',Phone = '"+phone+"',Picture = '"+picture+"' where Id = '"+id+"';"
		cursor.execute(sql)
		db_con.commit()
	
	except Exception, e:
		print('<p>Something went wrong with the SQL!</p>')
        print(sql, "\nError:", e)
	else:
`		print(html)
update()


FFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF
