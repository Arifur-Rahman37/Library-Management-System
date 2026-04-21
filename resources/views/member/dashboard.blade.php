@extends('layouts.app')

@section('title', 'Member Dashboard')

@section('content')
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Animated Gradient Background - Sky Blue Theme */
body {
    font-family: 'Inter', 'Poppins', sans-serif;
    min-height: 100vh;
    position: relative;
    overflow-x: hidden;
    background: linear-gradient(
        270deg,
        #87CEEB,
        #00BFFF,
        #1E90FF,
        #4169E1,
        #00CED1,
        #87CEEB
    );
    background-size: 400% 400%;
    animation: skyGradient 10s ease infinite;
}

@keyframes skyGradient {
    0% {
        background-position: 0% 50%;
    }
    25% {
        background-position: 50% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    75% {
        background-position: 50% 50%;
    }
    100% {
        background-position: 0% 50%;
    }
}

/* Floating Particles Animation */
body::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    pointer-events: none;
    animation: particleFloat 20s linear infinite;
    z-index: 0;
}

@keyframes particleFloat {
    0% {
        transform: translateY(0px);
    }
    100% {
        transform: translateY(-100px);
    }
}

/* Soft Wave Animation */
body::after {
    content: '';
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 150px;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.15)" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,154.7C960,171,1056,181,1152,165.3C1248,149,1344,107,1392,85.3L1440,64L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') repeat-x;
    background-size: cover;
    pointer-events: none;
    animation: waveMove 8s linear infinite;
    z-index: 0;
}

@keyframes waveMove {
    0% {
        background-position: 0% 0%;
    }
    100% {
        background-position: 100% 0%;
    }
}

/* Modern Navbar */
.navbar {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.3);
    padding: 1rem 0;
    position: relative;
    z-index: 100;
}

.navbar-brand {
    font-weight: 800;
    font-size: 1.8rem;
    background: linear-gradient(135deg, #00BFFF 0%, #1E90FF 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent !important;
    letter-spacing: -0.5px;
    transition: all 0.3s;
}

/* Glassmorphism Cards */
.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 24px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    z-index: 1;
}

.card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    background: rgba(255, 255, 255, 0.98);
}

/* Modern Buttons with Glow Effect */
.btn {
    border-radius: 14px;
    padding: 12px 32px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    z-index: 1;
}

.btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
    z-index: -1;
}

.btn:hover::before {
    width: 300px;
    height: 300px;
}

.btn-primary {
    background: linear-gradient(135deg, #00BFFF 0%, #1E90FF 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(0, 191, 255, 0.4);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 191, 255, 0.6);
}

/* Gradient Stat Cards */
.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 24px;
    padding: 25px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    transition: all 0.3s;
    z-index: 1;
}

.stat-card::before {
    content: '📚';
    position: absolute;
    right: -20px;
    bottom: -20px;
    font-size: 100px;
    opacity: 0.15;
    transition: all 0.3s;
}

.stat-card:hover::before {
    transform: scale(1.2) rotate(10deg);
}

/* Book Cards with Hover Effect */
.book-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
    z-index: 1;
}

.book-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

/* Custom Scrollbar with Gradient */
::-webkit-scrollbar {
    width: 12px;
}

::-webkit-scrollbar-track {
    background: linear-gradient(180deg, #87CEEB 0%, #00BFFF 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #00BFFF 0%, #1E90FF 100%);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #1E90FF 0%, #00BFFF 100%);
}

/* Footer with Gradient */
footer {
    background: linear-gradient(135deg, #87CEEB 0%, #00BFFF 100%);
    color: white;
    padding: 30px 0;
    margin-top: 60px;
    position: relative;
    z-index: 1;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card, .stat-card, .book-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .stat-card h2 {
        font-size: 1.5rem;
    }
    
    .btn {
        padding: 8px 20px;
    }
    
    .navbar-brand {
        font-size: 1.3rem;
    }
}
</style>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Currently Borrowed</h5>
                        <h2>{{ $activeBorrows->count() }}</h2>
                    </div>
                    <i class="fas fa-book fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Overdue Books</h5>
                        <h2>{{ $stats['overdue'] }}</h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Fine</h5>
                        <h2>৳{{ number_format($stats['total_fine'], 2) }}</h2>
                    </div>
                    <i class="fas fa-money-bill fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-book-open"></i> Currently Borrowed Books</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Fine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeBorrows as $borrow)
                    <tr class="{{ now()->gt($borrow->due_date) ? 'table-warning' : '' }}">
                        <td>{{ $borrow->book->title }}</td>
                        <td>{{ $borrow->book->author }}</td>
                        <td>{{ $borrow->borrowed_at->format('d M Y') }}</td>
                        <td>
                            {{ $borrow->due_date->format('d M Y') }}
                            @if(now()->gt($borrow->due_date))
                                <span class="badge bg-danger ms-1">Overdue</span>
                            @endif
                        </td>
                        <td>
                            @if(now()->gt($borrow->due_date))
                                <span class="badge bg-danger">Overdue</span>
                            @else
                                <span class="badge bg-success">Active</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $fine = 0;
                                if(now()->gt($borrow->due_date)) {
                                    $daysOverdue = now()->diffInDays($borrow->due_date);
                                    $fine = $daysOverdue * 5;
                                }
                            @endphp
                            ৳{{ number_format($fine, 2) }}
                        </td>
                        <td>
                            <form action="{{ route('member.borrows.return', $borrow->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Return this book?')">
                                    <i class="fas fa-undo"></i> Return
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-book-open fa-2x text-muted mb-2 d-block"></i>
                            No books borrowed currently
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5><i class="fas fa-history"></i> Borrow History</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Author</th>
                        <th>Borrowed Date</th>
                        <th>Returned Date</th>
                        <th>Fine Paid</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($borrowHistory as $borrow)
                    <tr>
                        <td>{{ $borrow->book->title }}</td>
                        <td>{{ $borrow->book->author }}</td>
                        <td>{{ $borrow->borrowed_at->format('d M Y') }}</td>
                        <td>{{ $borrow->returned_at ? $borrow->returned_at->format('d M Y') : '-' }}</td>
                        <td class="{{ $borrow->fine_amount > 0 ? 'text-danger fw-bold' : '' }}">
                            ৳{{ number_format($borrow->fine_amount, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-history fa-2x text-muted mb-2 d-block"></i>
                            No borrow history
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $borrowHistory->links() }}
        </div>
    </div>
</div>
@endsection