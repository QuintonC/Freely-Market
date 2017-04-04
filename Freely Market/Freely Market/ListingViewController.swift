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
    var BuyData: [[String]] = []
    var EquipmentData: [[String]] = []
    var RentalData: [[String]] = []
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        BuyData = []
        EquipmentData = []
        RentalData = []
        
        // Do any additional setup after loading the view, typically from a nib.
        
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(ListingViewController.dismissKeyboard))
        view.addGestureRecognizer(tap)
        
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        buyListings()
        rentListings()
        equipmentListings()
        
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return BuyData.count
        return EquipmentData.count
        return RentalData.count
    }
    
    func buyListings() {
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
                                    
                                    self.BuyData.append([title, "$" + price])
                                    
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
    
    func rentListings() {
        let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/rentalListings.php")!
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
                                    
                                    self.RentalData.append([title, "$" + price])
                                    
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
    
    func equipmentListings() {
            let requestURL: NSURL = NSURL(string: "http://cgi.soic.indiana.edu/~team12/api/equipmentListings.php")!
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
                                        
                                        self.EquipmentData.append([title, "$" + price])
                                        
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
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        
        let buy = tableView.dequeueReusableCell(withIdentifier: "buy", for: indexPath) as! CellData
        let rent = tableView.dequeueReusableCell(withIdentifier: "rent", for: indexPath) as! CellData
        let equipment = tableView.dequeueReusableCell(withIdentifier: "equipment", for: indexPath) as! CellData
        
        buy.contentView.backgroundColor = UIColor.clear
        rent.contentView.backgroundColor = UIColor.clear
        equipment.contentView.backgroundColor = UIColor.clear
        
        let whiteRoundedView : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        
        whiteRoundedView.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.75])
        whiteRoundedView.layer.masksToBounds = false
        whiteRoundedView.layer.cornerRadius = 5.0
        whiteRoundedView.layer.shadowOffset = CGSize(width: 1, height: 1)
        whiteRoundedView.layer.shadowOpacity = 0.1
        
        buy.contentView.addSubview(whiteRoundedView)
        buy.contentView.sendSubview(toBack: whiteRoundedView)
        rent.contentView.addSubview(whiteRoundedView)
        rent.contentView.sendSubview(toBack: whiteRoundedView)
        equipment.contentView.addSubview(whiteRoundedView)
        equipment.contentView.sendSubview(toBack: whiteRoundedView)
        
        buy.listingTitle.text = BuyData[indexPath.row][0]
        buy.listingPrice.text = BuyData[indexPath.row][1]
        rent.listingTitle.text = RentalData[indexPath.row][0]
        rent.listingPrice.text = RentalData[indexPath.row][1]
        equipment.listingTitle.text = EquipmentData[indexPath.row][0]
        equipment.listingPrice.text = EquipmentData[indexPath.row][1]
        
        return buy
        return rent
        return equipment
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
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
