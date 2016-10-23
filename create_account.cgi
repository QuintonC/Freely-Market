#! /usr/bin/env python

import cgi, MySQLdb

print ('Content-type: text/html\n')

form = cgi.FieldStorage()

string = "i211u16_pekirby" 	
pword = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=pword, db=string)

cursor = db_con.cursor()

html = """<!doctype html>
<html>
<head><meta charset="utf-8">
<title>Account Add</title></head>
    <body>
	<form action="create_account.html" method="get">
	<h1>{text}</h1>
	<a href = "http://cgi.soic.indiana.edu/~pekirby/show_account.cgi">Edit Account Information</a>
</html>"""

username = form.getfirst('user','user123')
password = form.getfirst('password','000')
first_name = form.getfirst('fname','John')
last_name = form.getfirst('lname','Smith')
birthday = form.getfirst('bday','1994-08-10')
address = form.getfirst('address','7322 N 126th Ave')
phone = form.getfirst('phone','3093451123')
picture = form.getfirst('picture','')



def insert_account(username,password,first_name,last_name,birthday,address,phone,picture):
	try:
		sql = "insert into Accounts(Username,Password,FirstName,LastName,Birthday,Address,Phone,Picture) values('"+username+"','"+password+"','"+first_name+"','"+last_name+"','"+birthday+"','"+address+"','"+phone+"','"+picture+"');"
		cursor.execute(sql)
		db_con.commit()
	except Exception, e:
		print('<p>Something went wrong with the SQL!</p>')
        print(sql, "\nError:", e)
	else:
		print(html.format(text = 'Your account was successfully created!'))
		
insert_account(username,password,first_name,last_name,birthday,address,phone,picture)