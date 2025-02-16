<?php 
use App\Models\Admin\Settings; 
$companyName = Settings::get('company_name');
$logo = Settings::get('logo');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $companyName; ?></title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>

</head>

<body>

    <div>
        <center>
            
            <div style="max-width: 600px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); line-height: 20px; font-family: Helvetica, Arial, sans-serif; color: #2F3751;">
                <table cellpadding="0" cellspacing="0" style="width: 100%; line-height: inherit; text-align: left;font-size: 14.5px">
                    <tr>
                        <td colspan="6" style="padding-bottom: 20px;">
                            <?php if($logo): ?>
                                <img src="<?php echo url($logo) ?>" style="max-width: 250px; max-height: 250px;"  alt="<?php echo $companyName ?>" >
                            <?php else: ?>
                                <?php echo $companyName ?>
                            <?php endif; ?><br/><br/>
                        </td>
                        
                    </tr>
                    <tr>
                        <td colspan="6" style="padding-bottom: 20px;">
                            <?php echo $content ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-size: 12px;text-align:center;background-color:#f7eddd;">Thank you, <b>Pinders Schoolwear Ltd.</b></td></tr>
                </table>
            </div>
        </center>
    </div>

</body>

</html>