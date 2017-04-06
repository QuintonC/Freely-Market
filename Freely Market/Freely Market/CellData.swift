//
//  CellData.swift
//  Freely Market
//
//  Created by Quinton Chester on 1/26/17.
//  Copyright © 2017 Freely Creative. All rights reserved.
//

import UIKit

class CellData: UITableViewCell {

    
    @IBOutlet weak var listingImage: UIImageView!
    @IBOutlet weak var listingTitle: UILabel!
    @IBOutlet weak var listingPrice: UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        // Initialization code
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
        
        let focusStyle : UIView = UIView(frame: CGRect(x: 10, y: 8, width: self.frame.size.width - 200, height: 10))
        
        focusStyle.layer.backgroundColor = CGColor(colorSpace: CGColorSpaceCreateDeviceRGB(), components: [1.0, 1.0, 1.0, 0.8])
        focusStyle.layer.masksToBounds = false
        focusStyle.layer.cornerRadius = 5.0
        focusStyle.layer.shadowOffset = CGSize(width: 1, height: 1)
        focusStyle.layer.shadowOpacity = 0.1
        
        self.selectedBackgroundView = focusStyle
    }

}
