<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate - {{ $enrollment->course->title }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .certificate {
            background: white;
            border: 20px solid #ffd700;
            padding: 50px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header { color: #2c3e50; }
        .student-name { color: #e74c3c; font-size: 2.5em; margin: 20px 0; }
        .course-title { color: #3498db; font-size: 1.8em; margin: 20px 0; }
        .signature { margin-top: 50px; }
        .print-btn { margin: 20px; padding: 10px 20px; }
    </style>
</head>
<body>
    <div class="certificate">
        <h1 class="header">CERTIFICATE OF COMPLETION</h1>
        <p>This is to certify that</p>
        <h2 class="student-name">{{ auth()->user()->name }}</h2>
        <p>has successfully completed the course</p>
        <h3 class="course-title">"{{ $enrollment->course->title }}"</h3>
        <p>with a final progress score of <strong>{{ $enrollment->progress_percentage }}%</strong></p>
        <p>Completed on: <strong>{{ now()->format('F d, Y') }}</strong></p>
        
        <div class="signature">
            <p>_________________________</p>
            <p><strong>Director, Kidicode LMS</strong></p>
        </div>
        
        <div class="mt-4">
            <button class="print-btn" onclick="window.print()">Print Certificate</button>
            <button class="print-btn" onclick="downloadPDF()">Download as PDF</button>
            <a href="{{ route('home') }}" class="print-btn">Back to Dashboard</a>
        </div>
    </div>

    <script>
        function downloadPDF() {
            alert('PDF download functionality would be implemented here.');
            // In real implementation: Generate and download PDF
        }
        
        // Auto-print option
        @if(request()->has('print'))
        window.onload = function() {
            window.print();
        }
        @endif
    </script>
</body>
</html>