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
    }

}
