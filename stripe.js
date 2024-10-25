const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY); // Clé secrète Stripe
const DOMAIN_NAME = process.env.YOUR_DOMAIN; // URL dommaine de votre site

exports.createCheckoutSession = async (req, res) => {
    try {
        const session = await stripe.checkout.sessions.create({
            line_items: [
                {
                    price: req.body.priceId, // Récupérer l'ID de prix depuis le corps de la requête
                    quantity: 1,
                },
            ],
            mode: "payment",
            success_url: `${DOMAIN_NAME}?success=true`,
            cancel_url: `${DOMAIN_NAME}?canceled=true`,
        });

        res.redirect(303, session.url);
    } catch (error) {
        console.error("Error creating checkout session:", error);
        res.status(500).send("Internal Server Error");
    }
};
