<?php

declare(strict_types=1);

namespace App\Services;

class WhatsappMessageBuilder
{
    /**
     * Build a human-readable order message.
     *
     * @param  array  $order  Order array as returned by OrderService::generate()
     * @return string
     */
    public function build(array $order): string
    {
        $lines   = [];
        $lines[] = "🛍 Pedido {$order['id']}";
        $lines[] = '';

        foreach ($order['items'] as $item) {
            $total   = number_format($item['qty'] * $item['price'], 2, ',', '.');
            $variant = !empty($item['variant']) ? " ({$item['variant']})" : '';
            $lines[] = "• {$item['title']}{$variant} x{$item['qty']} — REF {$total}";
        }

        $lines[] = '';
        $lines[] = 'Subtotal: REF ' . number_format($order['subtotal'], 2, ',', '.');
        $lines[] = '';
        $lines[] = "Nombre: {$order['customer']['name']}";

        if (!empty($order['customer']['location'])) {
            $lines[] = "Sector: {$order['customer']['location']}";
        }

        return implode("\n", $lines);
    }

    /**
     * Build the wa.me URL with the message encoded.
     *
     * @param  string  $message    Plain-text message from build()
     * @param  string  $waNumber   Raw WhatsApp number (may contain spaces, dashes, +)
     * @return string
     */
    public function url(string $message, string $waNumber): string
    {
        $clean = $this->waClean($waNumber);
        return 'https://wa.me/' . $clean . '?text=' . rawurlencode($message);
    }

    /**
     * Strip everything except digits from the WhatsApp number.
     */
    private function waClean(string $waNumber): string
    {
        return preg_replace('/\D/', '', $waNumber);
    }
}
