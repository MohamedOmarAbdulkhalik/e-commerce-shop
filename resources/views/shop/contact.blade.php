@extends('layouts.app')

@section('title', 'Contact Us - Barista Cafe')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section d-flex justify-content-center align-items-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1445116572660-236099ec97a0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80'); background-size: cover; padding: 100px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12 mx-auto text-center">
                    <h1 class="text-white mb-4">Contact Us</h1>
                    <p class="text-white">We'd love to hear from you! Get in touch with our team.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form Section -->
    <section class="contact-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-4">
                            <h3 class="card-title text-center mb-0">Send us a Message</h3>
                        </div>
                        
                        <div class="card-body p-5">
                            {{-- ✅ الرسائل هنا في الأعلى تكون واضحة --}}
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <div>
                                            <strong>Success!</strong> {{ session('success') }}
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                                        <div>
                                            <strong>Error!</strong> {{ session('error') }}
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                        <div>
                                            <strong>Please fix the following errors:</strong>
                                            <ul class="mb-0 mt-2">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('contact.submit') }}" method="POST">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="name" class="form-label">Full Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name') }}" 
                                                   placeholder="Enter your full name" 
                                                   minlength="3"
                                                   maxlength="255"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Minimum 3 characters required</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="email" class="form-label">Email Address *</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" name="email" value="{{ old('email') }}" 
                                                   placeholder="Enter your email address" 
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label">Your Message *</label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" name="message" rows="5" 
                                              placeholder="Tell us how we can help you..." 
                                              maxlength="500"
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text"><span id="charCount">0</span>/500 characters</div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-send-fill me-2"></i>Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="row mt-5">
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="text-center">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-geo-alt-fill display-4 text-primary"></i>
                        </div>
                        <h5>Visit Us</h5>
                        <p class="text-muted">123 Coffee Street<br>City, State 12345</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="text-center">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-telephone-fill display-4 text-primary"></i>
                        </div>
                        <h5>Call Us</h5>
                        <p class="text-muted">+1 (555) 123-4567<br>Mon-Fri: 8AM-6PM</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="text-center">
                        <div class="contact-icon mb-3">
                            <i class="bi bi-envelope-fill display-4 text-primary"></i>
                        </div>
                        <h5>Email Us</h5>
                        <p class="text-muted">info@baristacafe.com<br>support@baristacafe.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .contact-section {
        padding: 80px 0;
    }
    
    .card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
    }
    
    .contact-icon {
        width: 80px;
        height: 80px;
        background-color: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .form-control {
        border-radius: 10px;
        border: 2px solid #e9ecef;
        padding: 12px 16px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.2rem rgba(111, 66, 193, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(45deg, #6f42c1, #d63384);
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
    }
    
    .alert {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .alert-success {
        background: linear-gradient(45deg, #d1e7dd, #badbcc);
        color: #0f5132;
        border-left: 4px solid #0f5132;
    }
    
    .alert-danger {
        background: linear-gradient(45deg, #f8d7da, #f1aeb5);
        color: #842029;
        border-left: 4px solid #842029;
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<script>
    // عداد الأحرف للرسالة
    document.addEventListener('DOMContentLoaded', function() {
        const messageTextarea = document.getElementById('message');
        const charCount = document.getElementById('charCount');
        
        if (messageTextarea && charCount) {
            // تحديث العداد عند الكتابة
            messageTextarea.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = length;
                
                if (length > 500) {
                    charCount.classList.add('text-danger');
                    charCount.classList.remove('text-muted');
                } else {
                    charCount.classList.remove('text-danger');
                    charCount.classList.add('text-muted');
                }
            });
            
            // تهيئة العدد الأولي
            charCount.textContent = messageTextarea.value.length;
        }
    });
</script>
@endsection