#! /usr/bin/env python

import cgi, MySQLdb

print ('Content-type: text/html\n')

form = cgi.FieldStorage()
id = form.getfirst('id','1211')

string = "i211u16_pekirby" 	
pword = "my+sql=i211u16_pekirby" 	

db_con = MySQLdb.connect(host="db.soic.indiana.edu", port = 3306, user=string, passwd=pword, db=string)

cursor = db_con.cursor()

html = """<!doctype html>
<html>
<head><meta charset="utf-8">
<title>Account Table</title></head>
    <body>
	<table border = '1'>
	<th>
		<td>Id</td>
		<td>Username</td>
		<td>Password</td>
		<td>FirstName</td>
		<td>LastName</td>
		<td>Birthday</td>
		<td>Address</td>
		<td>Phone</td>
		<td>Picture</td>
		<td>Delete</td>
		<td>Edit</td>
	</th>
	<h2>Accounts</h2>
	{table}
    </table>
</html>"""
		
def show_table():
	try:
		sql = "select * from Accounts"
		cursor.execute(sql)
		results = cursor.fetchall()
	except Exception, e:
		print('<p>Something went wrong with the SQL!</p>')
        print(sql, "\nError:", e)
	else:
		table = ""
		for entry in results:
			table += "<tr>"
			table += "<td  align='center'>" +str(entry[0])+ "</td>"
			table += "<td  align='center'>" +str(entry[1])+ "</td>"
			table += "<td  align='center'>" +str(entry[2])+ "</td>"
			table += "<td  align='center'>" +str(entry[3])+ "</td>"
			table += "<td  align='center'>" +str(entry[4])+ "</td>"
			table += "<td  align='center'>" +str(entry[5])+ "</td>"
			table += "<td  align='center'>" +str(entry[6])+ "</td>"
			table += "<td  align='center'>" +str(entry[7])+ "</td>"
			table += "<td  align='center'>" +str(entry[8])+ "</td>"
			table += "<td  align='center'><a href = 'http://cgi.soic.indiana.edu/~pekirby/account_delete.cgi?id="+str(entry[0])+"'>Delete</a></td>"
			table += "<td  align='center'><a href = 'http://cgi.soic.indiana.edu/~pekirby/account_edit.cgi?id="+str(entry[0])+"'>Edit</a></td>"
			table += "</tr>"
		print(html.format(table = table))
		
show_table()
