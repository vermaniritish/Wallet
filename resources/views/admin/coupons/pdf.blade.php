<html>
    <head>
        <style>
            body { 
                font-family: Helvetica, Arial, sans-serif;
            }
			p {
				margin-bottom: 20px;
			}
			th{text-align: left;}
			b{font-weight: 800;}
        </style>
    </head>
    <body>
        <div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); line-height: 20px; font-family: Arial, sans-serif; color: #2F3751;">
            <table width="650" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2"><div align="center"><img src="<?php echo public_path('/frontend/assets/img/logo/logo-workwear.jpg') ?>" /></div></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:20px;font-family: Arial, Helvetica, sans-serif;font-size: 16px;"><p class="style1">Dear Sir/Madam</p><br/>
                <p>Please find below your unique voucher code for use at&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br /><br/>
                    <a href="https://www.pindersworkwear.com/" target="_blank">www.pindersworkwear.com</a> </p><br/>
                <p>This is a <strong>{{$page->title}}</strong> discount code for the amount of {{$page->is_percentage ? ($page->amount . '%') : '&pound;'.$page->amount }}.</p><br/>
                <p>Please ensure you use the full amount as the code will not be valid for any future purchases.</p><br/>
                <p>This code will expire on {{_d($page->end_date)}}.</p><br/>
                <p>Unique Voucher Code:</p><br/>
                <p align="center"><strong>{{$page->coupon_code}}</strong></p><br/><br/>
                <p align="center" class="style1">Thank you for shopping with Pinders Workwear.</p></td>
            </tr>
            <tr>
                <td style="padding:20px;font-family: Arial, Helvetica, sans-serif;font-size: 13px;color: #666666;">Pinders Schoolwear Ltd<br />
                TEL; 0114 2513275<br />
                www.pindersworkwear.com<br /></td>
                <td><div align="center"><img src="https://pindersschoolwear.com/image/emaillogo2.jpg" /></div></td>
            </tr>
            </table>
        </div>
</body>
</html>