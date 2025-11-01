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
        <div style="max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee;line-height: 20px; font-family: Arial, sans-serif; color: #2F3751;">
            <table width="650" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="2" style="padding-top: 20px;padding-bottom: 30px"><div align="center">
                    @if($logo)
                    <img src="<?php echo public_path($logo) ?>" style="height: 60px;" />
                    @endif
                </div></td>
            </tr>
            <tr>
                <td colspan="2" style="padding:20px;font-family: Arial, Helvetica, sans-serif;font-size: 16px;"><p class="style1">Parent/Guardian</p><br/>
                
                <p>Please find below your unique voucher code for use at&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br /><br/>
                <a href="https://pindersschoolwear.com/" target="_blank">www.pindersschoolwear.com</a> </p><br/>
                <p>This is a <strong>{{$page->title}}</strong> discount voucher for the amount of {{$page->is_percentage ? ($page->amount . '%') : '£'.$page->amount }}.</p><br/>
                <p>Please ensure you use the full amount as the code will not be valid for any future purchases.</p><br/>
                <p>This code will expire on {{_d($page->end_date)}}.</p><br/>
                <p>Unique Voucher Code:</p><br/>
                <p align="center"><strong>{{$page->coupon_code}}</strong></p><br/><br/>
                <p align="center" class="style1">Thank you for shopping with Pinders Schoolwear.</p></td>
                </td>
            </tr>
            
            </table>
        </div>
</body>
</html>