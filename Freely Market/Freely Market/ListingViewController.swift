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
    var TableData:Array< String > = Array < String >()
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(RegisterViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        get_data_from_url("http://cgi.soic.indiana.edu/~team12/api/buyListings.php")
        
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    
    //let url = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/buyListings.php")
    //let data = NSData(contentsOf: url! as URL)
    //var err: NSError?
    //do {
    //values = try JSONSerialization.jsonObject(with: data! as Data, options: .mutableContainers) as! NSArray
    //} catch let error as NSError {
    //print(err = error)
    //}
    //tableView.reloadData()
    //print(values)
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return TableData.count
    }

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let cell = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        
        cell.listingTitle.text = TableData[indexPath.row]
        cell.listingPrice.text = TableData[indexPath.row]
        cell.listingImage.image = UIImage(named: TableData[indexPath.row])
        
        //let maindata = values[indexPath.row] as! [String:Any]
        
        //cell.listingTitle.text = (maindata["title"] as AnyObject) as? String
        //cell.listingPrice.text = (maindata["price"] as AnyObject) as? String
        //cell.listingImage.image = (maindata["picture"] as AnyObject) as? UIImage
        
        return cell
    }
    
    func get_data_from_url(_ link:String) {
        let url:URL = URL(string: link)!
        let session = URLSession.shared
        let request = NSMutableURLRequest(url: url)
        request.httpMethod = "GET"
        request.cachePolicy = NSURLRequest.CachePolicy.reloadIgnoringLocalAndRemoteCacheData
        
        let task = session.dataTask(with: request as URLRequest, completionHandler: {(
            data, response, error) in
            
            guard let _:Data = data, let _:URLResponse = response, error == nil else {
                return
            }
            self.extract_json(data!)
        })
        task.resume()
    }
    
    func extract_json(_ data:Data) {
        
        let json: Any?
        
        do {
            json = try JSONSerialization.jsonObject(with: data, options: [])
        } catch {
            return
        }
        
        guard let data_list = json as? NSArray else {
            return
        }
        
        if let listings = json as? NSArray {
            for i in 0 ..< data_list.count {
                if let listings_obj = listings[i] as? NSDictionary {
                    if let title = listings_obj["title"] as? String {
                        if let price = listings_obj["price"] as? String {
                            TableData.append(title + " " + price)
                        }
                    }
                    
                }
            }
        }
        DispatchQueue.main.async(execute: {self.do_table_refresh()})
    }
    
    func do_table_refresh() {
        self.tableView.reloadData()
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
