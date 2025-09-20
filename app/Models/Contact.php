<?php

// app/Models/Contact.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $primaryKey = 'contact_id';

    protected $fillable = [
        'company_name',
        'address',
        'phone_primary',
        'phone_secondary',
        'email_primary',
        'email_secondary',
        'whatsapp',
        'operational_hours',
        'is_active'
    ];

    protected $casts = [
        'operational_hours' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the default operational hours structure
     */
    public static function getDefaultOperationalHours()
    {
        return [
            'monday' => ['open' => '08:00', 'close' => '17:00', 'is_open' => true],
            'tuesday' => ['open' => '08:00', 'close' => '17:00', 'is_open' => true],
            'wednesday' => ['open' => '08:00', 'close' => '17:00', 'is_open' => true],
            'thursday' => ['open' => '08:00', 'close' => '17:00', 'is_open' => true],
            'friday' => ['open' => '08:00', 'close' => '17:00', 'is_open' => true],
            'saturday' => ['open' => '08:00', 'close' => '12:00', 'is_open' => true],
            'sunday' => ['open' => '00:00', 'close' => '00:00', 'is_open' => false],
        ];
    }

    /**
     * Format operational hours for display
     */
    public function getFormattedOperationalHours()
    {
        $days = [
            'monday' => 'Senin',
            'tuesday' => 'Selasa', 
            'wednesday' => 'Rabu',
            'thursday' => 'Kamis',
            'friday' => 'Jumat',
            'saturday' => 'Sabtu',
            'sunday' => 'Minggu'
        ];

        $formatted = [];
        
        foreach ($this->operational_hours as $day => $hours) {
            if (isset($days[$day])) {
                $formatted[] = [
                    'day' => $days[$day],
                    'hours' => $hours['is_open'] ? 
                        $hours['open'] . ' - ' . $hours['close'] . ' WIB' : 
                        'Tutup'
                ];
            }
        }

        return $formatted;
    }

    /**
     * Get the active contact information
     */
    public static function getActive()
    {
        return self::where('is_active', true)->first();
    }

    /**
     * Format phone number for display
     */
    public function getFormattedPhonePrimary()
    {
        return $this->formatPhoneNumber($this->phone_primary);
    }

    public function getFormattedPhoneSecondary()
    {
        return $this->phone_secondary ? $this->formatPhoneNumber($this->phone_secondary) : null;
    }

    /**
     * Format WhatsApp number for URL
     */
    public function getWhatsAppUrl($message = '')
    {
        $number = preg_replace('/[^0-9]/', '', $this->whatsapp);
        if (substr($number, 0, 1) === '0') {
            $number = '62' . substr($number, 1);
        }
        
        $url = "https://wa.me/{$number}";
        if ($message) {
            $url .= "?text=" . urlencode($message);
        }
        
        return $url;
    }

    /**
     * Helper method to format phone numbers
     */
    private function formatPhoneNumber($phone)
    {
        // Simple formatting - you can enhance this based on your needs
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if (substr($phone, 0, 2) === '62') {
            return '+' . substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . '-' . substr($phone, 5, 4) . '-' . substr($phone, 9);
        } elseif (substr($phone, 0, 1) === '0') {
            return '(' . substr($phone, 0, 4) . ') ' . substr($phone, 4, 3) . '-' . substr($phone, 7);
        }
        
        return $phone;
    }
}