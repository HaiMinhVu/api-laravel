<html>
<body>
    <strong>Product Registration Confirmation</strong><br>
    Congratulations on your new purchase. Below is your product registration info. Please save for your records.<br>
    <h4>Customer Info</h4>
    <table border='1'>
        <tr>
            <td>Name</td>
            <td>{{ $productRegistration->customerName() }}</td>
        </tr>
        <tr>
            <td>Address</td>
            <td>{{ $productRegistration->fullAddress() }}</td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>{{ $productRegistration->phone_number }}</td>
        </tr>
    </table>
    <h4>Order Info</h4>
    <table border='1'>
        <tr>
            <td>Model</td>
            <td>{{ $productRegistration->product->sku }}</td>
        </tr>
        <tr>
            <td>Serial</td>
            <td>{{ $productRegistration->serial_number }}</td>
        </tr>
        <tr>
            <td>Dealer/Store</td>
            <td>{{ $productRegistration->DealerStore }}</td>
        </tr>
        <tr>
            <td>Price Paid</td>
            <td>{{ $productRegistration->price_paid }}</td>
        </tr>
        <tr>
            <td>Date Purchased</td>
            <td>{{ $productRegistration->date_purchased }}</td>
        </tr>
    </table>
</body>
</html>
