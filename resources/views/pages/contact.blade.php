@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-12 w-full">
    <div class="text-center mb-16">
        <h1 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Get in Touch</h1>
        <p class="text-xl text-slate-500 max-w-2xl mx-auto">Have questions or need assistance? Our team is here to help you.</p>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 max-w-5xl mx-auto">
        {{-- Contact Info --}}
        <div class="lg:w-1/3 flex flex-col gap-4">
            <div class="bg-primary text-white rounded-3xl p-8 shadow-xl shadow-primary/20 flex flex-col items-start gap-6 h-full relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-xl"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full -ml-8 -mb-8 blur-xl"></div>
                
                <h3 class="text-2xl font-bold relative z-10">Contact Information</h3>
                <p class="text-blue-100 relative z-10">Fill up the form and our team will get back to you within 24 hours.</p>
                
                <div class="flex flex-col gap-6 mt-8 relative z-10 flex-grow">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px] text-blue-200">call</span>
                        <span>+234 800 000 0000</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px] text-blue-200">mail</span>
                        <span>support@onlinemarket.ng</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-[24px] text-blue-200">location_on</span>
                        <span>123 Market Street, Victoria Island, Lagos</span>
                    </div>
                </div>

                <div class="flex gap-4 relative z-10 mt-auto pt-8">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white flex items-center justify-center hover:text-primary transition-colors text-white font-bold text-center leading-10">fb</a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white flex items-center justify-center hover:text-primary transition-colors text-white font-bold text-center leading-10">ig</a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white flex items-center justify-center hover:text-primary transition-colors text-white font-bold text-center leading-10">tw</a>
                </div>
            </div>
        </div>

        {{-- Contact Form --}}
        <div class="lg:w-2/3">
            <div class="bg-white rounded-3xl border border-slate-200 p-8 md:p-10 shadow-soft h-full">
                <form class="flex flex-col gap-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">First Name</label>
                            <input type="text" class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Last Name</label>
                            <input type="text" class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                            <input type="email" class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Phone</label>
                            <input type="tel" class="w-full h-12 px-4 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Message</label>
                        <textarea rows="5" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:ring-2 focus:ring-primary/20 focus:border-primary"></textarea>
                    </div>
                    <div>
                        <button type="button" onclick="alert('Message sent successfully!')" class="h-12 px-8 bg-primary hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all">Send Message</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
