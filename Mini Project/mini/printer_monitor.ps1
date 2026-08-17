Write-Host "Printer Monitoring Started..."

$lastRecordId = 0

while ($true)
{
    $events = Get-WinEvent -LogName "Microsoft-Windows-PrintService/Operational" -MaxEvents 5 -ErrorAction SilentlyContinue

    foreach ($event in $events)
    {
        # Detect only successful print
        if ($event.Id -eq 842 -and $event.RecordId -gt $lastRecordId)
        {

            $message = $event.Message

            # Extract document name
            if ($message -match "Document\s+(.+?),")
            {
                $docName = $matches[1]
            }
            else
            {
                $docName = "Unknown"
            }

            # 🔥 ADD: Get Printer Name
            if ($message -match "on\s+(.+?)\s")
            {
                $printerName = $matches[1]
            }
            else
            {
                $printerName = "Unknown"
            }

            # 🔥 ADD: Get Toner Level (may vary by printer)
            try {
                $printer = Get-Printer -Name $printerName
                $toner = $printer.PrinterStatus   # fallback (not exact toner)
            }
            catch {
                $toner = "Unknown"
            }

            Write-Host "Print Success: $docName | Toner: $toner"

            # Send document + toner
            $url = "http://localhost/printer_monitor/insert_print.php?doc=$docName&toner=$toner"

            Invoke-WebRequest $url

            $lastRecordId = $event.RecordId
        }
    }

    Start-Sleep -Seconds 3
}