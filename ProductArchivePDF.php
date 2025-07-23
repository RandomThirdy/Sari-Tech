<?php
require_once('components/connect.php');
require_once('libs/fpdf.php');

if (isset($_GET['export_pdf'])) {
    class ArchivePDF extends FPDF {
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

        function CheckPageBreak($h) {
            if($this->GetY() + $h > $this->PageBreakTrigger) {
                $this->AddPage($this->CurOrientation);
            }
        }
    }

    // Create PDF
    $pdf = new ArchivePDF('P', 'mm', 'A4');
    $pdf->AddPage();
    $pdf->SetMargins(10, 10, 10);

    // Store Header
    $pdf->SetFont('Courier', 'B', 16);
    $pdf->Cell(0, 10, 'SARI-TECH', 0, 1, 'C');
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(0, 5, 'Sapang I, Ternate, Cavite', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Tel: +63 912 345 6789', 0, 1, 'C');
    $pdf->Cell(0, 5, 'Email: contact@sari-tech.com', 0, 1, 'C');

    // Separator
    $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
    $pdf->Ln(5);

    
    // Report Title
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(0, 8, 'ARCHIVED PRODUCTS REPORT', 0, 1, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(0, 6, 'Generated Date: ' . date('Y-m-d H:i:s'), 0, 1, 'L');

    // Separator
    $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
    $pdf->Ln(5);

    // Table Headers
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(15, 8, 'ID', 0, 0, 'L');
    $pdf->Cell(60, 8, 'Name', 0, 0, 'L');
    $pdf->Cell(35, 8, 'Category', 0, 0, 'L');
    $pdf->Cell(25, 8, 'Price', 0, 0, 'R');
    $pdf->Cell(20, 8, 'Stock', 0, 0, 'R');
    $pdf->Cell(35, 8, 'Date Deleted', 0, 1, 'R');

    // Separator
    $pdf->DrawDashedLine(10, $pdf->GetY(), 200, $pdf->GetY());
    $pdf->Ln(2);

    // Fetch and Display Products
    $export_query = $conn->prepare("SELECT id, name, category, price, stock, date_deleted 
                                   FROM `product_archive` 
                                   ORDER BY date_deleted DESC");
    $export_query->execute();

    $pdf->SetFont('Courier', '', 10);
    $total_items = 0;
    $total_stock = 0;
    $total_value = 0;

    while ($row = $export_query->fetch(PDO::FETCH_ASSOC)) {
        // Check if need new page
        $pdf->CheckPageBreak(6);

        // Format data
        $name = substr(strip_tags($row['name']), 0, 35);
        $category = substr(strip_tags($row['category']), 0, 20);
        $price = number_format($row['price'], 2);
        $stock = (int) $row['stock'];
        $date = date("Y-m-d H:i", strtotime($row['date_deleted']));

        // Print row
        $pdf->Cell(17, 6, $row['id'], 0, 0, 'L');
        $pdf->Cell(62, 6, $name, 0, 0, 'L');
        $pdf->Cell(35, 6, $category, 0, 0, 'L');
        $pdf->Cell(25, 6, $price, 0, 0, 'R');
        $pdf->Cell(17, 6, $stock, 0, 0, 'R');
        $pdf->Cell(40, 6, $date, 0, 1, 'R');

        // Update totals
        $total_items++;
        $total_stock += $stock;
        $total_value += ($row['price'] * $stock);
    }

    // Separator
    $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
    $pdf->Ln(5);

    // Summary Section
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(0, 8, 'SUMMARY', 0, 1, 'L');
    $pdf->SetFont('Courier', '', 10);
    
    $pdf->Cell(0, 6, 'Total Archived Items: ' . $total_items, 0, 1, 'L');
    $pdf->Cell(0, 6, 'Total Stock: ' . number_format($total_stock), 0, 1, 'L');
    $pdf->Cell(0, 6, 'Total Inventory Value: PHP ' . number_format($total_value, 2), 0, 1, 'L');

    // Separator
    $pdf->DrawDashedLine(10, $pdf->GetY()+2, 200, $pdf->GetY()+2);
    $pdf->Ln(8);

    // Footer
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(0, 6, 'This is a system generated report', 0, 1, 'C');
    $pdf->Cell(0, 6, 'Generated on: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
    $pdf->Ln(4);
    $pdf->SetFont('Courier', 'B', 12);
    $pdf->Cell(0, 8, 'SARI-TECH INVENTORY SYSTEM', 0, 1, 'C');

    // Output PDF
    $pdf->Output('D', 'archived_products_' . date('Y-m-d') . '.pdf');
    exit;
}
?>