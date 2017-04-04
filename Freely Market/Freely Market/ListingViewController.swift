//
//  ViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class ListingViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    var values = NSArray()
    var TableData: [[String]] = []
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        TableData = []
        
        // Do any additional setup after loading the view, typically from a nib.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/buyListings.php")!
        let urlRequest: NSMutableURLRequest = NSMutableURLRequest(url: requestURL as URL)
        let session = URLSession.shared
        let task = session.dataTask(with: urlRequest as URLRequest) {
            (data, response, error) -> Void in
            
            let httpResponse = response as! HTTPURLResponse
            let statusCode = httpResponse.statusCode
            
            if (statusCode == 200) {
                
                do {
                    
                    let json = try JSONSerialization.jsonObject(with: data!, options: .allowFragments) as! [String:AnyObject]
                    
                    if let listings = json["listing"] as? [[String: AnyObject]] {
                        
                        for listing in listings {
                         
                            if let title = listing["item"] as? String {
                                
                                if let price = listing["price"] as? String {
                                    
                                    self.TableData.append([title, "$" + price])
                                    //print (title, price)
                                    print(self.TableData)
                                }
                            }
                            
                        }
                    }
                    
                    DispatchQueue.main.async {
                        self.tableView.reloadData()
                    }
                } catch {
                    print("Error with JSON: \(error)")
                }
        
            }
        }
        
        task.resume()
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return TableData.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        
        cell.listingTitle.text = TableData[indexPath.row][0]
        cell.listingPrice.text = TableData[indexPath.row][1]
        
        return cell
    }
    
    @IBAction func logout(_ sender: AnyObject) {
        
        let alertController = UIAlertController(title: "Success", message: "You have been logged out.", preferredStyle: .alert)
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in
            self.performSegue(withIdentifier: "logoutSegue", sender: self)
        }
        alertController.addAction(OKAction)
        self.present(alertController, animated: true, completion:nil)
        
    }
    
    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    
}
