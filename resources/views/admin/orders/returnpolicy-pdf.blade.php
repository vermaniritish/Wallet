<?php
use App\Models\Admin\Settings;
use Carbon\Carbon;
$currency = Settings::get('currency_symbol');
?>

<style>
    body { font-family: Helvetica, Arial, sans-serif; color: #122246; }
    .invoice-container { width: 100%; padding: 20px; border: 1px solid #ddd; }
    .title { font-size: 18px; font-weight: 700; text-align: center; margin: 10px 0; }
    .section-title { background: #f7eddd; padding: 6px 10px; font-weight: 700; font-size: 14px; border-bottom: 1px solid #c7a162; }
    .box { border: 1px solid #c7a162; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; font-size: 13px; }
    td, th { padding: 6px; vertical-align: top; }
    .item-row { border-bottom: 1px solid #eee; }
    .total-row { background: #f7eddd; font-weight: 700; border-top: 1px solid #c7a162; }
    .right { text-align: right; }
    .center { text-align: center; }
    .small{font-size: 11px}
</style>

<div class="invoice-container">

    <!-- Header -->
    <table>
        <tr>
            <td style="width:70%;">
                @if($logo)
                    <img src="<?php echo public_path($logo) ?>" style="height: 50px;">
                @endif
            </td>
            <td style="width:30%; font-size: 12px;">
             
            </td>
        </tr>
    </table>

  

<div class="container">
    <h1>Return Policy</h1>

    <p>
        You may return any unused and/or unworn items within <strong>28 days of purchase</strong>, 
        provided it is in a saleable condition with the original receipt, for an exchange or refund. 
        All tags must still be attached.
    </p>

    <p>
        All refunds will be processed using the original payment method.
    </p>

    <p>
        If you do not have the original credit or debit card, your refund will be issued with a 
        <strong>Pinders Schoolwear credit note</strong>.
    </p>

    <p>
        Pinders will not exchange or refund any garment which has been personalised.
    </p>

    <h3>Returning Your Items</h3>
    <p>Goods can be returned in store or via post.</p>

    <h3>Postal Returns Address</h3>
    <div class="address">
        <strong>Returns Department</strong><br>
        Pinders Schoolwear Ltd<br>
        Mansfield Road<br>
        Aston<br>
        Sheffield<br>
        S26 2BS
    </div>

    <p>
        Returns which are posted must include all original receipts.
    </p>

    <div class="contact">
        For more information please contact our reception team on 
        <a href="tel:01142513275">0114 2513275</a> 
        or email us at 
        <a href="mailto:info@pindersschoolwear.co.uk">info@pindersschoolwear.co.uk</a>.
    </div>

    <p class="note">
        Please note: Our phone lines are exceptionally busy during July, August & September.
    </p>
</div>


    <!-- Footer -->
    <p class="center" style="font-size:12px; margin-top:20px; background:#f7eddd; padding:8px;">
       Many Thanks, <b>Pinders Schoolwear Ltd.</b>
    </p>

</div>
