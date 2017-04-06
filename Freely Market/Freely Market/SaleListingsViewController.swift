//
//  SaleListingsViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/5/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class SaleListingsViewController: UIViewController, UITableViewDataSource, UITableViewDelegate {
    
    var BuyData: [[String]] = []
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        BuyData = []
        
        // Do any additional setup after loading the view, typically from a nib.
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        buyListings()
    }
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return BuyData.count
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
                                    if let picture = listing["picture"] as? String {
                                        self.BuyData.append([title, "$" + price, picture])
                                    }
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
        let buy = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        buy.contentView.backgroundColor = UIColor.clear
        let cellStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.75])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.1
        buy.contentView.addSubview(cellStyle)
        buy.contentView.sendSubview(toBack: cellStyle)
        buy.listingTitle.text = BuyData[indexPath.row][0]
        buy.listingPrice.text = BuyData[indexPath.row][1]
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + BuyData[indexPath.row][2])!
        print(imageURL)
        let session = URLSession(configuration: .default)
        let downloadPicTask = session.dataTask(with: imageURL) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if let res = response as? HTTPURLResponse {
                    print("Downloaded image with response code \(res.statusCode)")
                    if let imageData = data {
                        let picture = UIImage(data: imageData)
                        buy.listingImage.image = picture
                    } else {
                        print("Couldn't get image: Image is nil")
                    }
                } else {
                    print("Couldn't get response code for some reason")
                }
            }
        }
        downloadPicTask.resume()
        return buy
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
