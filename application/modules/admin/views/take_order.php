<div id="content">
    <div class="row">
        <?= form_open($action, $attributes); ?>
        <div class="col-lg-8">
            <section class="panel corner-flip">
                <div class="panel-body">
                    <div class="form-group" style="position: relative">
                        <input type="text" class="form-control item_name_order" placeholder="Enter Item Name or Code">
                        <div class="search-list"></div>
                    </div>
                </div>
            </section>
            <section class="panel corner-flip">
                <div class="panel-body">
                    <table cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Item Name</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                        </thead>
                        <tbody align="center" class="order_list">
                        <?php foreach ($this->cart->contents() as $rows): ?>
                            <tr>
                                <td><a data-id="<?= $rows['rowid']; ?>" class="remove_item"><i class="fa fa-close"></i></a>
                                </td>
                                <td><input type="hidden" name="product_id[]"
                                           value="<?= $rows['product_id'] ?>"><?= $rows['name']; ?></td>
                                <td><input type="hidden" name="price[]"
                                           value="<?= $rows['price'] ?>"><?= settings('currency') . '. ' . format_price($rows['price']); ?>
                                </td>
                                <td><input type="text" name="qty[]" value="<?= $rows['qty']; ?>"
                                           class="form-control qty-input"></td>
                                <td><input type="hidden" name="total[]"
                                           value="<?= $rows['subtotal'] ?>"><?= settings('currency') . '. ' . format_price($rows['subtotal']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-lg-4">
            <section class="panel corner-flip">
                <div class="panel-body">
                    <div class="form-group customer_name_input" style="position:relative;">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <a href="" class="btn btn-default"><i class="fa fa-user-plus"></i></a>
                            </span>
                            <input type="text" class="form-control customer" placeholder="Enter Customer Name / Code">
                        </div>
                        <div class="customer-list"></div>
                    </div>
                    <div class="customer_detail">
                        <strong>No Customer Select</strong>
                    </div>
                </div>
            </section>
            <section class="panel corner-flip">
                <div class="panel-body">
                    <ul class="cart-total-detail">
                        <li>
                            <ul>
                                <li><input type="checkbox" class="tax_checkbox" name="tax_checkbox"> GST: <input
                                        type="text"
                                        name="tax_amount"
                                        class="tax"
                                        value="<?= settings('tax_rate') ?>"
                                        readonly>%
                                </li>
                                <li><input type="checkbox" class="service_checkbox" name="service_checkbox"> Service
                                    Charge: <input type="text"
                                                   name="service_charge"
                                                   class="service"
                                                   value="<?= settings('service_charge') ?>"
                                                   readonly>%
                                </li>
                                <li><span class="help-block">Please check the box to include the charge</span></li>
                            </ul>
                        </li>
                        <li>
                            <ul>
                                <li> Discount: <input type="text" name="discount_amount" class="discount" value="0">
                                </li>
                                <li> Delivery Charge: <input type="text" name="delivery_charge" class="delivery"
                                                             value="0"></li>
                            </ul>
                        </li>
                    </ul>

                    <div class="grand-total-wrap">
                        <p><input type="hidden" class="grand-total" name="grand_total"
                                  value="<?= $this->cart->total(); ?>"> Grand
                            Total: <?= settings('currency') . '. '; ?>
                            <span class="grand-total-disp"><?= format_price($this->cart->total()); ?></span></p>
                    </div>
                    <div class="form-group">
                        <label for="">Payment Type</label>
                        <div><?= $payment_type; ?></div>
                    </div>
                    <div class="cart-btn">
                        <a href="<?= base_url('admin/cart/clear'); ?>" class="btn btn-danger"><i
                                class="fa fa-trash-o"></i> Clear Cart</a>
                        <button type="submit" class="btn btn-success confirm-order"><i class="fa fa-hand-o-right"></i>
                            Order Confirm
                        </button>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?= form_close(); ?>
</div>
</div>