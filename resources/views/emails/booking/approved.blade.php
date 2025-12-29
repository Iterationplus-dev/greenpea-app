@component('mail::message')
# Booking Approved 🎉

Hi {{ $booking->user->name }},

Great news! Your apartment booking has been approved.

### 🏠 Apartment Details
- **Location:** {{ $booking->apartment->city }}
- **Apartment:** {{ $booking->apartment->title }}
- **Start Date:** {{ $booking->start_date }}
- **End Date:** {{ $booking->end_date }}

### 💰 Amount Due
₦{{ number_format($booking->total_price, 2) }}

@component('mail::button', ['url' => $booking->payment_link ?? '#'])
Pay Now
@endcomponent

Please complete your payment to secure this apartment.

Thanks for choosing us for your stay in Nigeria 🇳🇬
**Your Apartment Team**
@endcomponent
