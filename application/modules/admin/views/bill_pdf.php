<!--mpdf
    <htmlpageheader name="myheader">
    </htmlpageheader>

    <htmlpagefooter name="myfooter">
    </htmlpagefooter>

    <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
    <sethtmlpagefooter name="myfooter" value="on" />
mpdf-->
<div class="invoice">
    <div class="row">
        <table width="100%">
            <tr>
                <td><a href="#"> <?= show_image(settings('userfile'),'pdf_logo','$return')?> </a></td>
                <td style="text-align: right">
                    <h3>Bill NO. #<?= $order->bill_no; ?></h3>
                    <span>Date: <?= user_format_date(strtotime($order->date));?></span><br>
                    <span>Time: <?= $order->time;?></span>
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <br>
    <table class="item_list" width="100%">
        <thead>
        <tr>
            <th>#</th>
            <th width="60%" class="text-left">Item</th>
            <th>Price</th>
            <th>Qty</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody class="text-center">
        <?php
        $html = '';
        $i = 1;
        foreach ($order->order_list as $ol):
            $html .= '<tr>';
            $html .= '<td>' . $i++ . '</td>';
            $html .= '<td>' . $ol->name . '</td>';
            $html .= '<td>' . $ol->price . '</td>';
            $html .= '<td>' . $ol->qty . '</td>';
            $html .= '<td>' . $ol->total . '</td>';
            $html .= '</tr>';
        endforeach;
        echo $html;
        ?>
        </tbody>
    </table>
    <br><br>
    <div class="row">
        <div class="col-sm-6">
            <div class="align-lg-right">
                <ul>
                    <li>TAX Amount: <?= show_price(format_price($order->tax_amount)); ?></li>
                    <li>Service Charge: <?= show_price(format_price($order->service_charge)); ?></li>
                    <li>Discount : <?= show_price(format_price($order->discount_amount)); ?></li>
                    <li>Delivery Charge: <?= show_price(format_price($order->delivery_charge)); ?></li>
                    <li>Grand Total: <?= show_price(format_price($order->grand_total)); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>