<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\ContactMessage; // Bỏ comment nếu bạn muốn lưu vào DB

class ContactController extends Controller
{
    /**
     * Hiển thị trang Liên hệ.
     */
    public function index()
    {
        return view('contact.index');
    }

    /**
     * Xử lý form liên hệ gửi lên.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ], [
            'name.required' => 'Vui lòng nhập họ tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'subject.required' => 'Vui lòng nhập tiêu đề.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.min' => 'Nội dung tin nhắn phải có ít nhất 10 ký tự.',
        ]);

        // 2. Xử lý logic (ví dụ: Gửi email cho admin hoặc lưu vào CSDL)
        // Ví dụ lưu vào DB (cần tạo model và migration trước):
        // ContactMessage::create($validated);

        // Ví dụ gửi mail (đơn giản hóa):
        // \Mail::to(config('mail.from.address'))->send(new \App\Mail\ContactFormSubmitted($validated));

        // 3. Quay lại trang trước với thông báo thành công
        return back()->with('success', 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
