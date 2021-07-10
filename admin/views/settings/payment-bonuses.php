<div class="col-md-8">
    <div class="settings-header__table">
        <button type="button" class="btn btn-primary mg-b-20 pull-right" data-toggle="modal" data-target="#modalDiv" data-action="new_paymentbonus">New Payment Bonus</button>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>
                    Method Name
                </th>
                <th>
                    Upward
                </th>
                <th>
                    Bonus
                </th>
                <th></th>
            </tr>
        </thead>
        <tbody class="methods-sortable">
            <?php foreach($bonusList as $bonus): ?>
                <tr>
                    <td>
                        <?php echo $bonus["method_name"]; ?>
                    </td>
                    <td>
                        <?php echo $settings["csymbol"] . $bonus["bonus_from"] ?>
                    </td>
                    <td>
                        %<?php echo $bonus["bonus_amount"]; ?>
                    </td>
                    <td class="p-r">
                        <button type="button" class="btn btn-primary btn-xs pull-right edit-payment-method" data-toggle="modal" data-target="#modalDiv" data-action="edit_paymentbonus" data-id="<?php echo $bonus["bonus_id"]; ?>">Edit</button>
                    </td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>