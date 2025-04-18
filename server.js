const express = require('express');
const Razorpay = require('razorpay');
const cors = require('cors');
const app = express();

app.use(express.json());
app.use(cors());

const razorpay = new Razorpay({
    key_id: 'rzp_test_YFMHtqtR7lJY4y', // Your test key ID
    key_secret: 'rzp_test_your_secret_key' // Replace with your Razorpay test secret key
});

app.post('/create-order', async (req, res) => {
    const { amount } = req.body;
    const options = {
        amount: amount, // Amount in paise
        currency: 'INR',
        receipt: `receipt_${Date.now()}`
    };
    try {
        const order = await razorpay.orders.create(options);
        res.json({ id: order.id });
    } catch (error) {
        res.status(500).json({ error: error.message });
    }
});

app.post('/verify-payment', async (req, res) => {
    const { razorpay_payment_id, razorpay_order_id, razorpay_signature } = req.body;
    const secret = 'rzp_test_your_secret_key'; // Replace with your Razorpay test secret
    const crypto = require('crypto');
    const hmac = crypto.createHmac('sha256', secret);
    hmac.update(razorpay_order_id + '|' + razorpay_payment_id);
    const generatedSignature = hmac.digest('hex');

    if (generatedSignature === razorpay_signature) {
        res.json({ success: true });
    } else {
        res.status(400).json({ success: false, error: 'Signature verification failed' });
    }
});

app.listen(3000, () => console.log('Server running on port 3000'));