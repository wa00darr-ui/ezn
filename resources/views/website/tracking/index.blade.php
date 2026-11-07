@extends('layouts.website')

@section('title', 'استأذن | متابعة الطلب')
@section('page', 'tracking')

@section('content')
<div class="page-bg" id="lookup-panel">
    <div class="wrap lookup-shell">
        <div class="breadcrumb">
            <a href="{{ url('/') }}">الرئيسية</a> / متابعة طلب
        </div>

        <div class="form-card">
            <div class="card-head">
                <h2>متابعة طلب استئذان</h2>
                <p>أدخل الرقم الدراسي للطالب أو رقم جوال ولي الأمر المسجل في الطلب.</p>
            </div>

            <form id="lookup-form" method="POST" action="{{ route('website.tracking.lookup') }}" class="card-body">
                @csrf
                <div class="fields">
                    <div class="field full">
                        <label for="lookup-identifier">الرقم الدراسي للطالب أو جوال ولي الأمر</label>
                        <input
                            id="lookup-identifier"
                            name="identifier"
                            type="text"
                            inputmode="numeric"
                            dir="ltr"
                            maxlength="50"
                            value="{{ old('identifier', $identifier ?? '') }}"
                            placeholder="الرقم الدراسي أو 05xxxxxxxx"
                            @error('identifier') aria-invalid="true" aria-describedby="lookup-error" @enderror
                            required
                        >
                    </div>
                </div>

                @error('identifier')
                    <p id="lookup-error" class="error" role="alert">{{ $message }}</p>
                @enderror

                <button type="submit" class="btn lookup-submit">
                    <span data-lookup-idle>متابعة الطلب</span>
                    <span data-lookup-busy hidden>جارٍ البحث…</span>
                </button>

                @isset($requests)
                    @if($requests->isEmpty())
                        <p class="error" role="status">لم نعثر على طلب مطابق. تأكد من الرقم الدراسي أو رقم الجوال المسجل في الطلب.</p>
                    @else
                        <p class="hint" role="status">أحدث الطلبات المطابقة، بحد أقصى ٢٠ طلبًا. اضغط متابعة الطلب مرة أخرى لتحديث الحالة.</p>
                    @endif
                @endisset
            </form>
        </div>

        @isset($requests)
            @foreach($requests as $permissionRequest)
                @switch($permissionRequest->status)
                    @case('pending')
                        @include('website.tracking.pending', ['permissionRequest' => $permissionRequest])
                        @break
                    @case('approved')
                        @include('website.tracking.approved', ['permissionRequest' => $permissionRequest])
                        @break
                    @case('rejected')
                        @include('website.tracking.rejected', ['permissionRequest' => $permissionRequest])
                        @break
                    @case('completed')
                        @include('website.tracking.completed', ['permissionRequest' => $permissionRequest])
                        @break
                    @default
                        <article class="form-card">
                            <div class="card-head">
                                <h2>{{ $permissionRequest->status === 'cancelled' ? 'تم إلغاء الطلب' : 'الحالة غير متاحة' }}</h2>
                            </div>
                            <div class="card-body">
                                @include('website.tracking.summary', ['permissionRequest' => $permissionRequest])
                            </div>
                        </article>
                @endswitch
            @endforeach
        @endisset
    </div>
</div>
@endsection
