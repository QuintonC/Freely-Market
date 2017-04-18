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
    var postURL = String()
    var imagePath = String()
    
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

        listingTitle.text = lTitle
        listingPrice.text = price
        listingDescription.text = descr
        
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
    

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
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
