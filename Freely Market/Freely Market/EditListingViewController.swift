//
//  EditListingViewController.swift
//  Freely Market
//
//  Created by Quinton Chester on 4/17/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class EditListingViewController: UIViewController, UITextFieldDelegate, UITextViewDelegate, UIImagePickerControllerDelegate, UINavigationControllerDelegate {

    @IBOutlet weak var listingTitle: UITextField!
    @IBOutlet weak var listingPrice: UITextField!
    @IBOutlet weak var listingDescription: UITextView!
    @IBOutlet weak var listingImage: UIImageView!
    
    var lTitle = String()
    var image = String()
    var descr = String()
    var price = String()
    var plainPrice = String()
    var postURL = String()
    var imagePath = String()
    var listingType = String()

    var bid = String()
    var rid = String()
    var eid = String()
    var finID = String()

    var callURL = URL(string: "")

    var selectedTitle = String()
    var selectedPrice = String()
    var selectedImage = String()
    var selectedDescr = String()
    
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
    
    override func viewDidLoad() {
        super.viewDidLoad()

        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(CreateListingViewController.dismissKeyboard))
        listingTitle.text = lTitle
        listingPrice.text = price
        listingDescription.text = descr
        
        plainPrice = price.replacingOccurrences(of: "$", with: "")

        listingPrice.addTarget(self, action: #selector(priceFieldChanged), for: .editingChanged)

        // Setup for the text area for description
        self.listingDescription.layer.cornerRadius = 5.0
        
        // populate with data from listings screen
        let imageURL = URL(string: "http://cgi.soic.indiana.edu/~team12/images/" + image)
        let session = URLSession(configuration: .default)
        
        let downloadPicTask = session.dataTask(with: imageURL!) {
            (data, response, error) in
            if let e = error {
                print("Error download image: \(e)")
            } else {
                if (response as? HTTPURLResponse) != nil {
                    if let imageData = data {
                        DispatchQueue.main.async {
                            let picture = UIImage(data: imageData)
                            self.listingImage.image = picture
                            //Stop the loadingIndicator
                            //self.loadingIndicator.removeFromSuperview()
                        }
                    } else {
                        print("Couldn't get image: Image is nil")
                    }
                } else {
                    print("Couldn't get response code for some reason")
                }
            }
        }
        downloadPicTask.resume()

        // GET ID OF THE LISTING
        if listingType == "Rental_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getRID.php")
            rid = finID
        } else if listingType == "Equipment_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getEID.php")
            eid = finID
        } else if listingType == "Buy_Listing" {
            callURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/getBID.php")
            //deleteURL = URL(string: http://cgi.soic)
            bid = finID
        } else {
            print ("Something went wrong, could not retrieve ID of the listing.")
        }

        var request = URLRequest(url: callURL!)
        request.httpMethod = "POST"
        let postString = "item=\(lTitle)&price=\(plainPrice)&descr=\(descr)&picture=\(image)"

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
                    DispatchQueue.main.async {
                        let baseID = ("\(String(describing: parseJSON["ID:"]))")
                        let halfID = baseID.replacingOccurrences(of: "Optional(", with: "")
                        self.finID = halfID.replacingOccurrences(of: ")", with: "")
                    }
                }
            } catch let error as NSError {
                print(err = error)
            }
        }
        task.resume()
    }
    
    func dismissKeyboard() {
        view.endEditing(true)
    }

    func imagePickerController(_ picker: UIImagePickerController, didFinishPickingMediaWithInfo info: [String : Any]) {
        let selectedImage = info[UIImagePickerControllerEditedImage] as? UIImage
        let imageURL = info[UIImagePickerControllerReferenceURL] as! NSURL
        
        listingImage.image = selectedImage
        self.dismiss(animated: true, completion: nil)
        
        let basePath = imageURL.path!
        let ucasePath = (basePath.replacingOccurrences(of: "/", with: "") as NSString) as String
        imagePath = (ucasePath.replacingOccurrences(of: "JPG", with: "jpg"))
        
    }
    
    func uploadImage() {
        let uploadURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/imageUpload.php")
        let request = NSMutableURLRequest(url: uploadURL!)
        request.httpMethod = "POST"
        let boundary = generateBoundaryString()
        request.setValue("multipart/form-data; boundary=\(boundary)", forHTTPHeaderField: "Content-Type")
        
        if (listingImage.image == nil) {
            return
        }
        
        let image_data = UIImageJPEGRepresentation(listingImage.image!, 1.0)
        
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
    
    func priceFieldChanged(_ textField: UITextField) {
        if let amountString = textField.text?.currencyInputFormatting() {
            textField.text = amountString
        }
    }
    
    @IBAction func selectImage(_ sender: Any) {
        let imagePicker = UIImagePickerController()
        imagePicker.delegate = self
        imagePicker.allowsEditing = true
        self.present(imagePicker, animated: true, completion: nil)
    }
    
    @IBAction func saveTapped(_ sender: Any) {
        uploadImage()

        let item:NSString = self.listingTitle.text! as NSString
        let price:NSString = self.listingPrice.text! as NSString
        let descr:NSString = self.listingDescription.text! as NSString
        let fixedPrice:NSString = price.replacingOccurrences(of: "$", with: "") as NSString

        if item.isEqual(to: "") || price.isEqual(to: "") || descr.isEqual(to: "") {
            let alertController = UIAlertController(title: "Oops!", message: "It seems you've forgotten something.", preferredStyle: .alert)
            let OKAction = UIAlertAction(title: "OK", style: .default) {
                (action:UIAlertAction) in
            }
            alertController.addAction(OKAction)
            self.present(alertController, animated: true, completion:nil)
        } else {
            // insert into database
            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/editListing.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"


            //print("Post string image path: " + postURL)
            
            let postString = "type=\(listingType)&item=\(item)&price=\(fixedPrice)&descr=\(descr)&picture=\(postURL)&id=\(self.finID)"

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

                        if messageToDisplay == "Could not edit listing" {
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
                        } else if messageToDisplay == "Listing edited successfully" {
                            DispatchQueue.main.async {

                                self.selectedTitle = item as String
                                self.selectedPrice = fixedPrice as String
                                self.selectedImage = self.postURL
                                self.selectedDescr = descr as String

                                self.performSegue(withIdentifier: "successfulEdit", sender: self)
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
        if (segue.identifier == "successfulEdit") {
            //Create an instance of the NavigationController
            let navVC = segue.destination as? UINavigationController
            //Create an instance of the destination IndividualListingViewController
            let listingVC = navVC?.viewControllers.first as! IndividualListingViewController

            //give the variables in the destination values from the current viewcontroller
            listingVC.lTitle = selectedTitle
            listingVC.image = selectedImage
            listingVC.descr = selectedDescr
            listingVC.price = selectedPrice
            listingVC.btnText = "Rent Now - " + selectedPrice
            listingVC.listingType = listingType
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    @IBAction func deleteListing(_ sender: Any) {

        let alertController = UIAlertController(title: "Are you sure?", message:"Your listing will be permanently deleted.", preferredStyle: .alert)
        let cancelAction = UIAlertAction(title: "Cancel", style: .cancel) {
            action -> Void in
        }
        let OKAction = UIAlertAction(title: "OK", style: .default) {
            (action:UIAlertAction) in

            let myURL = URL(string: "http://cgi.soic.indiana.edu/~team12/api/deleteListing.php")
            var request = URLRequest(url:myURL!)
            request.httpMethod = "POST"


            //print("Post string image path: " + postURL)

            let postString = "type=\(self.listingType)&id=\(self.finID)"

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

                        if messageToDisplay == "Could not delete listing" {
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
                        } else if messageToDisplay == "Listing deleted successfully" {
                            DispatchQueue.main.async {
                                self.performSegue(withIdentifier: "successfulDelete", sender: self)
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
        alertController.addAction(OKAction)
        alertController.addAction(cancelAction)
        self.present(alertController, animated: true, completion:nil)
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
