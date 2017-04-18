//
//  CreateListingViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/6/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class CreateListingViewController: UIViewController, UITextFieldDelegate, UITextViewDelegate, UIPickerViewDelegate, UIPickerViewDataSource, UIGestureRecognizerDelegate, UIImagePickerControllerDelegate, UINavigationControllerDelegate {

    @IBOutlet weak var menuButton: UIBarButtonItem!
    @IBOutlet weak var itemTitle: UITextField!
    @IBOutlet weak var itemPrice: UITextField!
    @IBOutlet weak var descBody: UITextView!
    @IBOutlet weak var listingTypeButton: UIButton!
    @IBOutlet weak var listingOptions: UIPickerView!
    @IBOutlet weak var doneButton: UIButton!
    @IBOutlet weak var selectedImageView: UIImageView!
    let pickerOptions = ["Rental Listing", "Sale Listing", "Equipment Listing"]
    var type = "none"
    var imagePath = String()
    var selectedTitle = String()
    var selectedPrice = String()
    var selectedImage = String()
    var selectedDescr = String()
    var selectedOwner = String()
    
    
    func getDate() -> String {
        let currentDate = NSDate()
        let dateFormatter = DateFormatter()
        dateFormatter.dateFormat = "MMMMddyyyyhhmmss"
        return dateFormatter.string(from: currentDate as Date)
    }
    
    
    var timestamp:String {
        return getDate()
        //"\(NSDate().timeIntervalSince1970 * 1000)"
    }
    var postURL = String()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        // Do any additional setup after loading the view.
        
        // Gesture recognizers for handling taps outside of keyboard or uipicker
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(CreateListingViewController.dismissKeyboard))
        let done: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(CreateListingViewController.doneTapped))
        tap.delegate = self
        done.delegate = self
        view.addGestureRecognizer(tap)
        view.addGestureRecognizer(done)
        
        // Handlers for menu button
        if self.revealViewController() != nil {
            menuButton.target = self.revealViewController()
            menuButton.action = #selector(SWRevealViewController.revealToggle(_:))
            self.view.addGestureRecognizer(self.revealViewController().panGestureRecognizer())
        }
        
        // Setup for the text area for description
        self.descBody.layer.cornerRadius = 5.0
        descBody.text = "Enter your item description here."
        descBody.textColor = UIColor.lightGray
        
        // Set delegates, etc for uipicker and hide some stuff
        listingOptions.delegate = self
        listingOptions.dataSource = self
        listingOptions.showsSelectionIndicator = true
        listingOptions.isHidden = true
        doneButton.isHidden = true
        
        // set targets for the buttons
        listingTypeButton.addTarget(self, action: #selector(CreateListingViewController.typeTapped), for: .touchUpInside)
        doneButton.addTarget(self, action: #selector(CreateListingViewController.doneTapped), for: .touchUpInside)
        
        // set target for the price field
        itemPrice.addTarget(self, action: #selector(priceFieldChanged), for: .editingChanged)
    }
    
    func textViewDidBeginEditing(_ textView: UITextView) {
        if descBody.textColor == UIColor.lightGray {
            descBody.text = nil
            descBody.textColor = UIColor.black
        }
    }
    
    func textViewDidEndEditing(_ textView: UITextView) {
        if descBody.text.isEmpty {
            descBody.text = "Enter your item description here."
            descBody.textColor = UIColor.lightGray
        }
    }
    
    func priceFieldChanged(_ textField: UITextField) {
        if let amountString = textField.text?.currencyInputFormatting() {
            textField.text = amountString
        }
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }
    
    func typeTapped() {
        listingOptions.isHidden = false
        doneButton.isHidden = false
    }
    
    func doneTapped() {
        listingOptions.isHidden = true
        doneButton.isHidden = true
    }
    
    // allow multiple gesture recognizers
    func gestureRecognizer(_ gestureRecognizer: UIGestureRecognizer, shouldRecognizeSimultaneouslyWith otherGestureRecognizer: UIGestureRecognizer) -> Bool {
        return true
    }
    
    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [String : Any]) {
        let selectedImage = info[UIImagePickerControllerEditedImage] as? UIImage
        let imageURL = info[UIImagePickerControllerReferenceURL] as! NSURL
        
        selectedImageView.image = selectedImage
        self.dismiss(animated: true, completion: nil)
        
        let basePath = imageURL.path!
        let ucasePath = (basePath.replacingOccurrences(of: "/", with: "") as NSString) as String
        imagePath = (ucasePath.replacingOccurrences(of: "JPG", with: "jpg"))
        
    }
    
    @IBAction func selectImage(_ sender: Any) {
        let imagePicker = UIImagePickerController()
        imagePicker.delegate = self
        imagePicker.allowsEditing = true
        self.present(imagePicker, animated: true, completion: nil)
    }
    
    func uploadImage() {
        let uploadURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/imageUpload.php")
        let request = NSMutableURLRequest(url: uploadURL!)
        request.httpMethod = "POST"
        let boundary = generateBoundaryString()
        request.setValue("multipart/form-data; boundary=\(boundary)", forHTTPHeaderField: "Content-Type")
        
        if (selectedImageView.image == nil) {
            return
        }
        
        let image_data = UIImageJPEGRepresentation(selectedImageView.image!, 1.0)
        
        if(image_data == nil) {
            return
        }
        
        let body = NSMutableData()
        
        let mimetype = USER + timestamp + "/jpg"
        postURL = USER + timestamp + ".jpg"
        
        body.append("--\(boundary)\r\n".data(using: String.Encoding.utf8)!)
        body.append("Content-Disposition:form-data; name=\"test\"\r\n\r\n".data(using: String.Encoding.utf8)!)
        body.append("hi\r\n".data(using: String.Encoding.utf8)!)
        body.append("--\(boundary)\r\n".data(using: String.Encoding.utf8)!)
        body.append("Content-Disposition:form-data; name=\"file\"; filename=\"\(postURL)\"\r\n".data(using: String.Encoding.utf8)!)
        body.append("Content-Type: \(mimetype)\r\n\r\n".data(using: String.Encoding.utf8)!)
        body.append(image_data!)
        body.append("\r\n".data(using: String.Encoding.utf8)!)
        body.append("--\(boundary)--\r\n".data(using: String.Encoding.utf8)!)
        
        request.httpBody = body as Data
        
//        let postString = "type=\(type)&item=\(item)&price=\(fixedPrice)&descr=\(descr)&owner=\(username)&picture=\(postURL)"
//        
//        request.httpBody = postString.data(using: String.Encoding.utf8)
        
        let session = URLSession.shared
        let task = session.dataTask(with: request as URLRequest, completionHandler: {(
            data, response, error) in
            
            guard ((data) != nil), let _:URLResponse = response, error == nil else {
                print("error")
                return
            }
            if let dataString = NSString(data: data!, encoding: String.Encoding.utf8.rawValue) {
                print(dataString)
            }
        })
        task.resume()
    }
    
    
    func generateBoundaryString() -> String {
        return "Boundary-\(UUID().uuidString)"
    }
    
    func numberOfComponents(in pickerView: UIPickerView) -> Int {
        return 1
    }
    
    func pickerView(_ pickerView: UIPickerView, numberOfRowsInComponent component: Int) -> Int {
        return pickerOptions.count
    }
    
    func pickerView(_ pickerView: UIPickerView, titleForRow row: Int, forComponent component: Int) -> String? {
        return pickerOptions[row]
    }
    
    func pickerView(_ pickerView: UIPickerView, didSelectRow row: Int, inComponent component: Int) {
        listingTypeButton.setTitle(pickerOptions[row], for: .normal)
        if pickerOptions[row] == "Rental Listing" {
            type = "Rental_Listing"
        } else if pickerOptions[row] == "Sale Listing" {
            type = "Buy_Listing"
        } else if pickerOptions[row] == "Equipment Listing" {
            type = "Equipment_Listing"
        } else {
            type = "none"
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    @IBAction func saveTapped(_ sender: Any) {
    
        uploadImage()
        let item:NSString = self.itemTitle.text! as NSString
        let price:NSString = self.itemPrice.text! as NSString
        let descr:NSString = self.descBody.text! as NSString
        let fixedPrice:NSString = price.replacingOccurrences(of: "$", with: "") as NSString
        let username = USER
        
        if item.isEqual(to: "") || price.isEqual(to: "") || descr.isEqual(to: "") || type.isEqual("none") {
            let alertController = UIAlertController(title: "Oops!", message: "It seems you've forgotten something.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        } else {
            // insert into database
            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/createListing.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"
            
            
            //print("Post string image path: " + postURL)
            
            let postString = "type=\(type)&item=\(item)&price=\(fixedPrice)&descr=\(descr)&owner=\(username)&picture=\(postURL)"
            
            request.httpBody = postString.data(using: String.Encoding.utf8)
            
            let task = URLSession.shared.dataTask(with: request as URLRequest) {
                (data, response, error) in
                
                if error != nil {
                    print("error is \(String(describing: error))")
                    return
                }
                
                var err: NSError?
                
                do {
                    let json = try JSONSerialization.jsonObject(with: data!, options: .mutableContainers) as? NSDictionary
                    
                    
                    if let parseJSON = json {
                        
                        let messageToDisplay:String = parseJSON["message"] as! String
                        let myAlert = UIAlertController(title: "Alert", message:messageToDisplay, preferredStyle: .alert)
                        
                        if messageToDisplay == "Could not create listing" {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else if messageToDisplay == "Could not locate aid" {
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        } else if messageToDisplay == "Listing created successfully" {
                            DispatchQueue.main.async {
                                self.selectedTitle = item as String
                                self.selectedPrice = price as String
                                self.selectedImage = self.postURL
                                self.selectedDescr = descr as String
                                self.selectedOwner = USER
                                
                                self.performSegue(withIdentifier: "successfulListing", sender: self)
                            }
                        } else { //Something else went wrong
                            DispatchQueue.main.async {
                                let OKAction = UIAlertAction(title: "OK", style: .default) {
                                    (action:UIAlertAction) in
                                }
                                myAlert.addAction(OKAction)
                                self.present(myAlert, animated: true, completion: nil)
                            }
                        }
                    }
                } catch let error as NSError {
                    print(err = error)
                }
            }
            task.resume()
        }
    }
    
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        if (segue.identifier == "successfulListing") {
            //Create an instance of the NavigationController
            let navVC = segue.destination as? UINavigationController
            //Create an instance of the destination IndividualListingViewController
            let listingVC = navVC?.viewControllers.first as! IndividualListingViewController
            
            //give the variables in the destination values from the current viewcontroller
            listingVC.lTitle = selectedTitle
            listingVC.image = selectedImage
            listingVC.descr = selectedDescr
            listingVC.owner = selectedOwner
            listingVC.price = selectedPrice
            listingVC.btnText = "Rent Now - " + selectedPrice
        }
    }

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}

extension String {
    
    func currencyInputFormatting() -> String {
        var number: NSNumber!
        let formatter = NumberFormatter()
        formatter.numberStyle = .currencyAccounting
        formatter.currencySymbol = "$"
        formatter.maximumFractionDigits = 2
        formatter.minimumFractionDigits = 2
        
        var amountWithPrefix = self
        
        let regex = try! NSRegularExpression(pattern: "[^0-9]", options: .caseInsensitive)
        amountWithPrefix = regex.stringByReplacingMatches(in: amountWithPrefix, options: NSRegularExpression.MatchingOptions(rawValue: 0), range: NSMakeRange(0, self.characters.count), withTemplate: "")
        
        let double = (amountWithPrefix as NSString).doubleValue
        number = NSNumber(value: (double / 100))
        
        guard number != 0 as NSNumber else {
            return ""
        }
        
        return formatter.string(from: number)!
    }
    
}

extension NSMutableData {
    
    func appendString(_ string: String) {
        let data = string.data(using: String.Encoding.utf8, allowLossyConversion: true)
        append(data!)
    }
}
