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
    var selectedTitle = String()
    var selectedPrice = String()
    var selectedImage = String()
    var selectedDescr = String()
    var selectedOwner = String()
    
    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var tableView: UITableView!
    
    override func viewDidLoad() {
        super.viewDidLoad()
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
                                    if let picture = listing["picture"] as? String {
                                        if let descr = listing["descr"] as? String {
                                            if let owner = listing["owner"] as? String {
                                                self.RentalData.append([title, "$" + price, picture, descr, owner])
                                            }
                                        }
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
        let rent = tableView.dequeueReusableCell(withIdentifier: "cell", for: indexPath) as! CellData
        rent.contentView.backgroundColor = UIColor.clear
        
        let cellStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.view.frame.size.width - 20, height: 85))
        
        cellStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.5])
        cellStyle.layer.masksToBounds = false
        cellStyle.layer.cornerRadius = 5.0
        cellStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        cellStyle.layer.shadowOpacity = 0.1
        
        rent.contentView.addSubview(cellStyle)
        rent.contentView.sendSubview(toBack: cellStyle)
        
        rent.listingTitle.text = RentalData[indexPath.row][0]
        rent.listingPrice.text = RentalData[indexPath.row][1]
        
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + RentalData[indexPath.row][2])!
        let session = URLSession(configuration: .default)
        
        let downloadPicTask = session.dataTask(with: imageURL) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if (response as? HTTPURLResponse) != nil {
                    if let imageData = data {
                        let picture = UIImage(data: imageData)
                        rent.listingImage.image = picture
                    } else {
                        print("Couldn't get image: Image is nil")
                    }
                } else {
                    print("Couldn't get response code for some reason")
                }
            }
        }
        downloadPicTask.resume()
        return rent
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
    
        selectedTitle = RentalData[indexPath.row][0] as String
        selectedPrice = RentalData[indexPath.row][1] as String
        selectedImage = RentalData[indexPath.row][2] as String
        selectedDescr = RentalData[indexPath.row][3] as String
        selectedOwner = RentalData[indexPath.row][4] as String
        
        print(selectedTitle)
        print(selectedPrice)
        performSegue(withIdentifier: "passSegue", sender: self)
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if (segue.identifier == "passSegue") {
            
            let destination = storyboard?.instantiateViewController(withIdentifier: "indListing") as! IndividualListingViewController
            
            //let destination = segue.destination as? IndividualListingViewController
            
            destination.lTitle = selectedTitle
            destination.image = selectedImage
            destination.descr = selectedDescr
            destination.owner = selectedOwner
            destination.price = selectedPrice
            
        }
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
