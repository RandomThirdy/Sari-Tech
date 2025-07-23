<?php
require_once('components/connect.php');
require_once('libs/fpdf.php');

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    // Get order details
    $get_order = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $get_order->execute([$order_id]);

    if ($get_order->rowCount() > 0) {
        $order = $get_order->fetch(PDO::FETCH_ASSOC);

        class ReceiptPDF extends FPDF {
            function Header() {
                // Empty header to allow content from top
            }

            function Footer() {
                // Empty footer to allow content to bottom
            }

            function DrawDashedLine($x1, $y1, $x2, $y2) {
                for($i=$x1; $i<$x2; $i+=3) {
                    $this->Line($i, $y1, $i+1, $y2);
                }
            }

            // Auto page break
            function CheckPageBreak($h) {
                if($this->GetY() + $h > $this->PageBreakTrigger) {
                    $this->AddPage($this->CurOrientation);
                }
            }
        }

        // Create PDF with auto-page height
        $pdf = new ReceiptPDF('P', 'mm', 'A4');  // Changed to A4 for better handling
        $pdf->AddPage();
        $pdf->SetMargins(10, 10, 10);  // Set margins

        // Store Header
        $pdf->SetFont('Courier', 'B', 16);
        $pdf->Cell(0, 10, 'SARI-TECH', 0, 1, 'C');
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(0, 5, 'Sapang I, Ternate, Cavite', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Tel: +63 912 345 6789', 0, 1, 'C');
        $pdf->Cell(0, 5, 'Email: contact@sari-tech.com', 0, 1, 'C');
        $pdf->Cell(0, 5, 'VAT Reg TIN: 123-456-789-000', 0, 1, 'C');

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
        $pdf->Ln(5);

        // Order Details
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(0, 8, 'ORDER INFORMATION', 0, 1, 'L');
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(0, 6, 'Receipt No: ' . str_pad($order['id'], 8, '0', STR_PAD_LEFT), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Date: ' . date('Y-m-d H:i:s', strtotime($order['placed_on'])), 0, 1, 'L');
        $pdf->Cell(0, 6, 'Cashier: ONLINE ORDER', 0, 1, 'L');

        // Customer Info
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Ln(5);
        $pdf->Cell(0, 8, 'CUSTOMER INFORMATION', 0, 1, 'L');
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(0, 6, 'Name: ' . $order['name'], 0, 1, 'L');
        $pdf->Cell(0, 6, 'Email: ' . $order['email'], 0, 1, 'L');
        $pdf->MultiCell(0, 6, 'Address: ' . $order['address'], 0, 'L');
        $pdf->Cell(0, 6, 'Phone: ' . $order['number'], 0, 1, 'L');

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
        $pdf->Ln(5);

        // Order Items Header
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(0, 8, 'ORDER DETAILS', 0, 1, 'L');
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->Cell(88, 8, 'Item Description', 0, 0, 'L');
        $pdf->Cell(30, 8, 'Quantity', 0, 0, 'R');
        $pdf->Cell(41, 8, 'Unit Price', 0, 0, 'R');
        $pdf->Cell(33, 8, 'Amount', 0, 1, 'R');

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(2);

        // Order Items
        $pdf->SetFont('Courier', '', 10);
        $products = explode(', ', $order['total_products']);
        foreach($products as $product) {
            $pdf->Cell(80, 6, substr($product, 0, 35), 0, 0, 'L');
            $pdf->Cell(30, 6, '1', 0, 0, 'R');
            $price_per_item = $order['total_price'] / count($products);
            $pdf->Cell(40, 6, number_format($price_per_item, 2), 0, 0, 'R');
            $pdf->Cell(40, 6, number_format($price_per_item, 2), 0, 1, 'R');
        }

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
        $pdf->Ln(5);

        // Totals
        $subtotal = $order['total_price'] / 1.12; // 12% VAT
        $vat = $order['total_price'] - $subtotal;

        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(150, 6, 'Subtotal:', 0, 0, 'R');
        $pdf->Cell(40, 6, number_format($subtotal, 2), 0, 1, 'R');
        $pdf->Cell(150, 6, 'VAT (12%):', 0, 0, 'R');
        $pdf->Cell(40, 6, number_format($vat, 2), 0, 1, 'R');

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
        $pdf->Ln(5);

        // Total and Payment
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(150, 8, 'TOTAL:', 0, 0, 'R');
        $pdf->Cell(40, 8, 'PHP ' . number_format($order['total_price'], 2), 0, 1, 'R');
        
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(150, 6, 'Payment Method:', 0, 0, 'R');
        $pdf->Cell(40, 6, $order['method'], 0, 1, 'R');

        if (!empty($order['payment_details'])) {
            $pdf->Cell(150, 6, 'Payment Details:', 0, 0, 'R');
            $pdf->Cell(40, 6, $order['payment_details'], 0, 1, 'R');
        }

        // Separator
        $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
        $pdf->Ln(8);

        // Footer
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(0, 6, 'This serves as your official receipt', 0, 1, 'C');
        $pdf->Cell(0, 6, 'Keep this receipt for warranty purposes', 0, 1, 'C');
        $pdf->Cell(0, 6, 'Returns and exchanges within 7 days with receipt', 0, 1, 'C');
        $pdf->Ln(4);
        $pdf->SetFont('Courier', 'B', 12);
        $pdf->Cell(0, 8, 'THANK YOU FOR SHOPPING!', 0, 1, 'C');
        $pdf->SetFont('Courier', '', 10);
        $pdf->Cell(0, 6, 'Visit us at: www.sari-tech.com', 0, 1, 'C');

        // Output PDF
        $pdf->Output('D', 'receipt_' . $order['id'] . '.pdf');
    } else {
        echo "Order not found!";
    }
} else {
    echo "Invalid request!";
}
?>