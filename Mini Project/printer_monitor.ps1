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

            Write-Host "Print Success: $docName"

            # Send document name to PHP
            $url = "http://localhost/insert_print.php?doc=$docName"

            Invoke-WebRequest $url

            $lastRecordId = $event.RecordId
        }
    }

    Start-Sleep -Seconds 3
}