#! /usr/bin/env python

import cgi, MySQLdb

print('Content-type: text/html\n')

form = cgi.FieldStorage()
id = form.getfirst('fid','0')

string = "i211u16_pekirby" 	
password = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=password, db=string)

cursor = db_con.cursor()


html = """<!doctype html>
<html>
<head><meta charset="utf-8">
<title>Create Account</title></head>
    <body>
	<h1>Create Account</h1>
	<form action="create_account.cgi" method="get">
	<input type="hidden" value="{Id}" name="id" />
	<p>User Name: <input type="text" name ="user" value = "{Username}"></p>
	<p>Password: <input type="text" name ="password" value = "{Password}"></p>
	<p>First Name: <input type="text" name="fname" value = "{FirstName}"></p>
	<p>Last Name: <input type="text" name="lname" value = "{LastName}"></p>
	<p>Birthday: <input type="text" name="bday" value = "{Birthday}"></p>
	<p>Address: <input type="text" name="address" value = "{Address}"></p>
	<p>Phone: <input type="text" name="phone" value = "{Phone}"></p>
	<input type="file" name="picture" accept="image/gif, image/jpeg, image/png" value ="{Picture}">
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
	Id, Username, Password, FirstName, LastName, Birthday, Address, Phone, Picture  = results
	
except Exception, e:
		print('<p>Something went wrong with the SQL!</p>')
        print(sql, "\nError:", e)
else:
`	print(html.format(Username = Username, Password = Password, FirstName = FirstName, LastName = LastName, Birthday = Birthday, Address = Address, Phone = Phone, Picture = Picture))
