@php
    $flashSuccess = session('success') ?? session('status');
    $flashError   = session('error');
    $flashInfo    = session('info');
    $hasErrors    = isset($errors) && $errors->any();
@endphp

@if ($flashSuccess || $flashError || $flashInfo || $hasErrors)
    <div class="frame flash-stack" role="status" aria-live="polite">
        @if ($flashSuccess)
            <div class="flash flash--success">
                <strong>Done.</strong>
                <span>{{ $flashSuccess }}</span>
            </div>
        @endif

        @if ($flashInfo)
            <div class="flash flash--info">
                <strong>Note.</strong>
                <span>{{ $flashInfo }}</span>
            </div>
        @endif

        @if ($flashError)
            <div class="flash flash--error" role="alert">
                <strong>Could not complete.</strong>
                <span>{{ $flashError }}</span>
            </div>
        @endif

        @if ($hasErrors)
            <div class="flash flash--error" role="alert">
                <strong>Please check these fields:</strong>
                <ul>
                    @foreach ($errors->all() as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
