@props(['url'])

<tr>
<td class="header"
    style="
        background: linear-gradient(135deg, #8a0b4e, #5c153d);
        padding: 40px 20px;
        text-align: center;
        border-radius: 18px 18px 0 0;
    ">

    <a href="{{ $url }}"
       style="
            display: inline-block;
            text-decoration: none;
       ">

            <img src="{{ rtrim(config('app.url'), '/') . '/image/logo-pranala.png' }}"
              alt="Logo"
              style="
                 width: 90px;
                 height: auto;
                 margin-bottom: 15px;
              ">

        <div
            style="
                color: white;
                font-size: 24px;
                font-weight: bold;
                letter-spacing: .5px;
            ">
            Sistem Lelang Digital
        </div>

        <div
            style="
                color: rgba(255,255,255,.9);
                font-size: 14px;
                margin-top: 6px;
            ">
            PT Pranala Digital Transmaritim
        </div>

    </a>
</td>
</tr>