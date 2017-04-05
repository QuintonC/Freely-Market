//
//  ViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 10/19/16.
//  Copyright © 2016 Freely Creative. All rights reserved.
//

import UIKit

class RentalListingsViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {

    var RentalData: [[String]] = []
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        RentalData = []
        
        // Do any additional setup after loading the view, typically from a nib.
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        rentListings()
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return RentalData.count
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

    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let rent = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        rent.contentView.backgroundColor = UIColor.clear
        let whiteRoundedView : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        whiteRoundedView.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.75])
        whiteRoundedView.layer.masksToBounds = false
        whiteRoundedView.layer.cornerRadius = 5.0
        whiteRoundedView.layer.shadowOffset = CGSize(width: 1, height: 1)
        whiteRoundedView.layer.shadowOpacity = 0.1
        rent.contentView.addSubview(whiteRoundedView)
        rent.contentView.sendSubview(toBack: whiteRoundedView)
        rent.listingTitle.text = RentalData[indexPath.row][0]
        rent.listingPrice.text = RentalData[indexPath.row][1]
        return rent
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
