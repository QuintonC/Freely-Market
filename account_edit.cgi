#! /usr/bin/env python

import cgi, MySQLdb

print('Content-type: text/html\n')

form = cgi.FieldStorage()
id = form.getfirst('id','2')

string = "i211u16_pekirby" 	
pword = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=pword, db=string)

cursor = db_con.cursor()


html = """<!doctype html>
<html>
<head><meta charset="utf-8">
<title>Create Account</title></head>
    <body>
	<h1>Create Account</h1>
	<form action="update_account.cgi" method="get">
	<input type="hidden" value="{id}" name="id" />
	<p>User Name: <input type="text" name ="user" value = "{username}"></p>
	<p>Password: <input type="text" name ="password" value = "{password}"></p>
	<p>First Name: <input type="text" name="fname" value = "{first_name}"></p>
	<p>Last Name: <input type="text" name="lname" value = "{last_name}"></p>
	<p>Birthday: <input type="text" name="bday" value = "{birthday}"></p>
	<p>Address: <input type="text" name="address" value = "{address}"></p>
	<p>Phone: <input type="text" name="phone" value = "{phone}"></p>
	<input type="file" name="picture" accept="image/gif, image/jpeg, image/png" value ="{picture}">
	<br />
	<br />
	<button type="submit">Create Account</button>
	<a href = "http://cgi.soic.indiana.edu/~pekirby/show_account.cgi">Go Back</a>
	</form>
    </body>
</html>"""



try:
	sql = "select * from Accounts where Id = "+str(id)+";"
	cursor.execute(sql)
	results = cursor.fetchone()
	id, username, password, first_name, last_name, birthday, address, phone, picture  = results
except Exception, e:
	print('<p>Something went wrong with the SQL!</p>')
    print(sql, "\nError:", e)
else:
	print(html.format(id = id, username = username, password = password, first_name = first_name, last_name = last_name, birthday = birthday, address = address, phone = phone, picture = picture))
