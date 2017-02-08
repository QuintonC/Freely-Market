//
//  ChatViewController.swift
//  Freely Market
//
//  Created by Austin Mitts on 1/30/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import Foundation
import UIKit
import WebKit


class ChatViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    
    
    @IBOutlet weak var tableView: UITableView!

    
    
    
    
    var values:NSArray = []
    var names = ["Paul", "Nancy", "Carlos"]
    var user = "oklightning"
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        //get()
        
        
        
        
        
        
        let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getContacts.php")
        var request = URLRequest(url:myURL!)
        request.httpMethod = "POST"
        
        let postString = "user=\(user)"
        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let task = URLSession.shared.dataTask(with: myURL!) { (data, response, error) in
            if error != nil {
                print("ERROR")
            } else {
                if let content = data {
                    do {
                        let myJson = try JSONSerialization.jsonObject(with: content, options: JSONSerialization.ReadingOptions.mutableContainers) as AnyObject
                        
                        if let sender = myJson["sender"] as! NSArray? {
                            print(sender)
                        }

                        
                        
                        
                        
                    }
                    catch {
                        
                    }
                }
            }
        }
        task.resume()
    
    }


    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
//    @IBAction func send(_ sender: Any) {
//        let message = txtMessage.text
//        txtMessage.text = ""
//        var temp = txtDisplay.text
//        temp = temp! + "\n"+message!
//        txtDisplay.text = temp
//    }
    
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! ContactCell
        
        cell.name.text = names[indexPath.row]
        
        return cell
    }
    
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return names.count
    }
    
}
