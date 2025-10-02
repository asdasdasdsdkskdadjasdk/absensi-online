<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="flex items-center justify-center min-h-screen bg-gray-100 dark:bg-gray-900">
            <div class="relative flex flex-col w-full max-w-4xl m-6 bg-white dark:bg-gray-800 shadow-2xl rounded-2xl md:flex-row md:space-y-0">
                
                <!-- Kolom Form Login -->
                <div class="flex flex-col justify-center p-8 md:p-14 w-full md:w-1/2">
                    <!-- Logo Gambar -->
                    <div class="flex justify-center mb-6">
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOAAAADgCAMAAAAt85rTAAABhlBMVEX////9//9+vjIoImImI2J+vTSAvTIoI2AmI2T///0oImQAAFAVDVonJGOgnbYAAE4AAFMiG1/LytQAAFYZFV/R0t3U09lhYX97e5kAAEvi4upRTXh7vSqylCKDwzTS0tJHP1i9njPm5uaysrLMzMy5okcqJlz5+Pw0M2tvb2/d3d3v7++Tk5P///gAAEf69+mmpba7u8yIiIi+vr6np6cmI09DVlNxbpIaDlddXV1pY4Z1tx2515HC257u7fPf786GvEqoz3tFQG6zs8Y1L2bv+uWSkKgAAEFKSHpfWoR+fn5bW1tLRW3S67qBtjeYw2Xp9NCVx1nt9du02IxUV2otLVWTx2LX5b7j7eN8n2NQcUnJ4Kp+rUJyl0svNFNahkVNY041R1Z8sUJre1idraI8UVN0aFdnemOqmEyijlBbgE2OeVTPxozGuHbu6c5eT1Gllkzi16ybh1Z9qVCMq3Y4R1EiLVNYSVxpiE5obn8uO1OGuV5oe2yHiJkqKSxEREQGBQwAADbM6aiMAAAZM0lEQVR4nO2diV8jx5WAS7f6QOpWt4QQPcIjGBoQAgmYSKOGBh20RxjEwMSwMZ7E6107iZPYm51N4swudvKf573qUxcMlyRYPc9vrFFf9fU76tXr6hIhE5nIRCYykYlMZCITmchEJjKRiUzkDsIQ31Gt3a4djbohDyb7x5ri9yvacWHULXkY2W8roZAfRDtmRt2WBxAfqWl+PwX0a0/RSn3rJhyKcjzq1jyE7CoOoD/8FL3wXHNVqL0ZdWseQN5orga181G35gHk1FWgX7kYdWseQk5cJ1S2R92Yh5BtD2Bt1I15CNl1nVA5GXVjHkIKYU8/8RRzGXLs2qj2FDtCsuMBPB11Y+5fGK8TPsmOkBQ8qcyT7Ag9Tqi8HXVbHkRcJ3yaHaHHCZWzUbflIcRXCDleGHqS/YTHCcP7o27Lg8iOY6NPsSMkXifUdkfdlgeRQvtpjwg9Tvg0R4SEXDiAT7MjdEtrMCL0ZaDn8I26Rfcsbjq6l6vMLJZG3Z77F9sJQ6FfylFRaOmjblB/ub1lOelo6PPoVDQoChnycecatjHvX+wc3aav3rXLv9nP2WAwOCXWrwdkznd2joab2u2faZqiaXs7N064nMKMBTglJa475KitKXCx7SGWcewHYYoW3t694XVtJzQBg0HeuOaAt5p5hHbMDA3xLAxihXvt7JzBekSv9G/ORTfg5dXX2razOyW8c/eWf5ycai4gIra3eyw12Shl+h9sp6MOYOyqSzHbmjvEGtoI5EKDKO950uBXlNob4gkWpSYnSfH+XYCdjtqAUmPwhXxgn54rDa1SBaG+ExDVuHexbm3O1CU2wLKsPNO37TWlE/CKIMMAn9/z3HRYD4YvegHNgENNSF8Tg8gXCEa5str3aLMfpIABYYApE5PPCzg0De52m6jDeLxbIAkhQgHZQCDALyZ7jn5jA0YpIDcol2GYGl7Hc35tWD5Y2FP6ApqW+m+SyYeAAZHr6QUsJ7QB44M6el9N6Th3SBteqfFIC/cHxNvc/tVL2QEMBIVDvSvNMhtuAbLsAMBCTety8/YQyzhHIaUvHZVs+wtRtAFBifGuMcMF7ShCLymgONsfsHCMVhJyLUVpD7WKs7/d1gYzZr/8BQ+RxhJWKHceawHSICOX+wIyZ+FQB6BW2x9mts34CLN/cRyCJLE/Yujdr2VbhYFA5fOOULmneACNfoCFE3+oA1DbIcNL1KjQqxV2t0+U/prMhn6zKNtKjIjxomfAU1NcE4VEphdw/aTjnGF/e3c0Y39MQvePaqH+jHtf8SLrmOm06iSnFx5AqdQLuH7SGV+0s33gG2FxAxS5p/WBzL7/d96xU5lzUpZTzQOY6AL0kf29jlMpOFDyjRSQmIps93hkNvvupe2KkQCXtnamPaEF2DuiB76wlw/Mkxmy/w2Qfh6JXYYctHyRr2dMM0UntAA5vUs1yOcB1E72mXEBRFm3FWk7UTj7NXQZdpc4U6SDjgsHMCp22d7+nt8zFPNrbyneuACaofV050zzzEvLZt9/sF2RlaYx/UYnDC1iN8E2O8PjKerPAVRCR2Sc1OeKGVptl1RCPy7ariiLEGuYtg2IiYwH8LQdDruAGD3HVwpvts+c0Jrd+8K2U5a7pE5oAZa9gKfhsAdQ2x73h6TM+nltz9IjdBmy5YlCXT+yATGRcQB3wx5AJfQ4Zpr4TrdP/Bh2stn/sHUoc/8ZzpqAfNEFdPnCGD0f0RPS9fO3e+H3v4iwATYgAmWQlb7ZMwGFkgPo4QsrCnbuo231zSTxSx7CDCvL3/4g40BKXPwVBXQrMu7DX9Df178tP57nMhDkG0sCDBADIv/D754///0ngBgVxUjUTGRMOXc7lmz7u4gsrl1b8R4XUWMiJwaxyvbJ758/e/782Ys/0E5jChOZeN7c6VxzSvrZd7/m2Wi0kn4cNpoxBB61x8qLf3jx7NkfC+t/evb8xbeiGETAaMWEOHLt8/03PBvBDuRRACarHE/rM6L87Yvnz/60jiHl+xfPnv/uB14EjGiE7mbzQV4HQ6xg5LEAlupxEYwtCtb5t/8C0/ye5l0MKfwZEP/7E5YmMoTWsEzrDH0nymzUBBx7E1WLTQnHu1R/hy+eP/+zlVJiarn+R7DTxSgmMgy5MB8/KOh8gWCUDjJoDznOohsVyYSjgJ88f1HwZM0M2OkzBIRExnzSAXhffsOj6izAqDSmD7epZNIcH4UhLsJFsYIIgPi9Z2CwTgFBTTv08Uq2Dc4XiUSmbEBujBWYaHEV1lEe/yHgAHrEBJRKyGdVqCIoZjJXWbvyodpIpVEXZNYxTv7Du/f8YED+f5QsGOi7D3wgErEBRUkwxtU+1WJEEKciQQtPFr9oZ9/JgwHl99T5KmwwYgOKkhjDQfEYjnJJLi05VTRqnV99nfVnfzMYMMC3wfkqYsQRkavjQ8VR19D6SrIsyVGnmA3W+Zcvsxj9fzsYMBrZ++4laNwWmWvheN838iJhPymvQfY1ZeFFo/xf34VxpKsdGVdocPGDHHDweK5spt5jCViWWPdZBCtHvmtnafJ1TspiQBzkgwHWqihGgjxv5Mn4Ji8Jzn1eBnHiK2qdfkXbZcisCbjeeYDZ0QetkqkoRYrqWM89BDVZgOh877O0DqOEdyEYQr4GgDRVM9sPeUzBStUoICsLr63h7fgC1u0HSSz/+Tt7jpCCRXcygxqyk20zFf3zc0y2RZqysDJXTY218qhYgNDzWc5H9YexQo/j985wCeT738Fw6W+8iScJ6YwZOEeNcLU0KaBYgZ7PqmriI2doN8kIlDwqugPeZzDgZYNRxDM7dTL+GkRA6nz+kFXWbO+beklIVr/P8k7J4qUcnZqaEqWlhm+c/c4raUHk//pjGB88U0DKx6AGSwgYicos9nR20SkyxcpS69GUlUB8xjR1PgvQ4kPDi/Fou3/5URShSxfFbz+Drh2yVP7vg2c4jaUUap6nn8qe+9TkUqaA+IiJJpsiHTJ89WX7ZGd3/YoTjpmcex87e/lIVTQBs3uXcXvMsPguq+Ak3tDxxf44jhq6xIezVl2+cAcfqVuAIe00YY8bWBmiLYiCc7+2z8f8IRJDCsee550h/96+d05Lk7UAlQuiTgvWqE8Wf2zTB8EhnKZ4djHGT1p8ZLdjVoSy5y0vEZ/gAB7DvSgKjhI/vMtmrdmEYK3tt7vjqUiG2Ql38J0UiPeZus5RwG8AMFTAfr/OW47IVr4xU3IaesdWkes1zfNUL4R8HZJxNOjXdn1MgYEu0x4CipUvaF5n9i340EwLvR0zj3zTVvweQOW40FVMSQi2Bv3ajjlfosQ7NQo58ptQ1gGkPaimgSLHI7T6sCituI2D1h0z3XPmSi6gUoNDILsheotzamgypnd+9xx4Gk3be3uEkXjUWVz3nFXlrIePFCs0C/9FFsJJm/ExZjWiGHcqFTA6/jrrD3eK368pZzuWR45MnadtrfPlghrj65nUkhYdQOgJrYISxJqmHWsiQTH4XSgb7hF8IcNU5IjkQlE6p4zX+s2Zo4lMgKWA0BNagD7iS3uUKP4aBsm9iGZo3RlB/4HTKOmUeA+h9rbg61MOq+PTTxvw2AHEYUZcdBBZCbuMPogUMlQbviJP9xR/x4xq7S3x9QMMeAD94YILCLFmVvJUfCtftfvYqRNa93a64/ODynlbCXdMGde2ia8foCrQapvpg37llPg8hMSIs7YjBln5JWRvEF76M/q1vTdkaLb6Bjv3jinj2x7b84oe9wCGlB0vIOyaXKzYgIBY+fC/IUXpTwhpQGhodlqgUx69U8a3yQDApNABeNwJ6CNqmXMAg0FxCWfzDyQc2pvp5zQ7swEh/INiBgAmJC+gP1TwdRGSxoxoPxYMBtd0sn50og3Q4tBe7dlWvIDhq5YvanRoEFew8HUjZuqCBRit1Gl1bbeG+XsfwGGtf/HWCwh8V0S3It8JuNMDCAmfsWa+5zRT1S1osNSwMjpArwYV0N8VKWPanENpAYYwHe1DmGwKkiS8TrobCXN+3GOpQwPcdX3wupcWzUTGAfS3u53Q8sRko5QkpPO7N2/bnYjDW0XoWLEBw0dXp/yHnYChPk7oRKbe7wo7e5prqdrwFjDBVzfC1KcurnmlqNUF2N0TXikMtdSwqUasFQwvldmv4ZRzbe/0uhn/MakT0H92A0BTjfs7bXqxWmGo8+/3L7Z3dukA9sqL6hLbCRju64RXIxZ2L7Yv9skIXp9grn9nozQjdgAquzcFpKZqXm0YTDeW5JLARvj/swBv5oQdehxTwfqEEF/a1+zXHbvT0ccOiKNjNUPW7TdWe9PRRw9IpeC8sTqoJ3zkgOQkfDcnHHX7r5WaA3g7Jxx1+6+Vbfu1+NDNe8JHAXih3M0JR93+a2VXcwB3RpGTPLicOhpUTp4k4L67dENo/ykCMm1n8Qb/2Lw3fq/irk6h7DxBQIbUXA0ePz1A4HE7QnxE8QQBL5x+AqujTxDQ7Qj9ytAXh3l4YXD1A4fw7MmZKMi6o0FMR58gYMHtCENPc5HtMxdQ2R7LV5PuKDWnnwj5T54ioNsRAuF4zde6H7nwRJmhPcocpux6TNT/iCYzu3KNWxUUtyN8fD+nkYzFUtft40aZsfrNno8KdzGB52eue296vW3pUBmvn3FtFFGuWv2E0dciU1PRmfzVJ/KdtjV8ZUurFXz9bptOL1RcuUtjbyNNied5oXrVLkmOnZqakq410sLR8dnx9u6AlbaScbgQPzP092HojNbKlct96zMAyMb7LOjbKc5iD30BSxKuEsAN+42YDL4eF5CuXqCnCDe//5rMvcIMeqpoyLiwh3iLNt5Jijx97fFK7TAkWSxm7pp+1UUA5NPX73i/soSzIYVrFqRnnL9uLwkBFRgf9hoIRXRB/vVH7eslHDjgG3gb6iK4IGfbeUet/gFHj8k4TsSaziRKIIkEBoA8/D+RKOGdzuAHMF49QYV2E5kkCoRCvWGky0Yx1Y2kJuD7GJ4pZR5mfp3mI3AnG9Z3CQRMlfCyuF1NuTviNYpGOY0Xzps7317rSbpgn9gSBQlEoH156lP8x1qJxCIcfPgUmppYw80ztJuIzeDnT1UjLvGyzHPcYkfwUdMzUgW/b6XILD1pi6BaDYHOOSxzAgocD9/CP2AHnuhVDj4JS9YpSvW4AKeWxCJZoRdb612t/ONENzhzvWx7JVtcpYdJcWBKUSkxLWHnN4ULhyWkKH6VQl3F6NvHoiFEp+i6W1GWm3UjVILjWZypHWBFzmhhVBGn8Uaas5vhStYMYA4PSWNYFZd0znwj1vKTMheIYAuirNSM4XzagHBbQENwFwT3ApprSvHmHM/+gFNLQWuSKy7PdOjwxUVnpRm2ItIlPRAw4lmvowuQfV0230e3AFu8c94gS99NCEi3BSzygwHt9+YHATYjLmBQMkxHzHMePntFHQRcCgwEjAbZoAfQkEzDMGXqjoCSLMr2wqDwSZY8gLigfVAUxfgAwIDISRWrbYEZMwykK+ZKMzIPriV6AJvglqKlRRE+yTMuIMgUXoivw13KzJgLzuEpODlwR8BStVwup6eQkG2m4XO1xHgAZSnSgu9yfQFFOd0oxfCXJ9C1zB920WcioPgIK8wWS6Xia0l0APHk5SolFFv4edoLyPLx19gSALQcQ15LNxKlNM8H7wJohfdZM5Da3zmAUtrJGnsB5aLZkTXiZuygEbDI07nLQsns2ZJNB5BKhs5Pt5JC3MEEnJLFhhmlGB8x36Php80vfIZ8Jw16AWcdbBMwKjTcDq4XULA3lTgKRX2qJeJnILAObIndgKBfwLdzVRNQbFrdHPClOOotzm81ZeIPBiiXPflFH0CnZwDDw2Qd+8hKIEJbZw93uwExaCOgLSagtxNo0MmLbuaffDhAKfGRgCWJAkIaosapO8YczX8UILvoaYvB08zYOfnDArpzO64CzAgR66ZDjEELbdwQcIm4YgKyzr8fFtCVKwE5Glga+JoPAgLAjUy0E7CCgEvOLRoLQFeDJuCdNEgB3TFxihsVIOcANngKiG3gMMjIaQewfmPAGDVRt6qREEYF6BagZmkUFXC830RAtmKzqxLbB5C7CrBEo6j7e3AG9PvQdd4DICvaEeXjAEX7Z16K9MUkNoIxNy0jq2xvMvh+gLJbPOgFzNNlB1i751BxmHM/gNAs3cxtPg5wSr7EO6JecjRVE2nVsUTdMSpUqYkVZ8Q+gBiFVCuL6gUkr81UhqOJkk5fO7kzoP1uBydVdeajAad4sVqe5fggArKWY1m/5CPGl6rVpuRJtqnonPmNxAXMInkfwAZdIQqSNb5VbsVpy+4MaPDWyIjl8VofCxiMwgggGqSA4ktzv6Jgnwq2wV8dgAwxR2csDoaLAwB9i6J7Blx5/R4AM5w9HGQx2FuAwtWAEUiSA8EpGNt4fYbM2gv640hSXOqIoj4sy5jDKdg2ABB6PvcMEblOffDWI3pLDPu+ewa80Y4CNAVkOd0TZGKCtRgxJB7ucrZq012fkytVEdAthDKq6PyQUdwZLsmtztY0Ztzf5KonJQScuWupMR2XzdGp5NRkpI4fw6IvskpmB2d19D4jTisNAZF3hw9AWI2bBR5xrQj6hLHvmudOZRYlaxhsAoLNil3PKnwkIQqiGUvragriUkC4e604UY5UUOK0qrZWqQQ6f+8qMSNIrPWVk8kk6nEsxDUNtaOumSxXJEmKz0JHeShVKrNeS2B8xdkAvRKtqqWFSqWe6Co7+oharM9wAscbMHz6tFJh+/721s1FBXE+da5kxzAqsUO7A8jQsmlSJz3rP6gZLLDit2q/udj0KqpD1bfmqycTSZXYs7kfYkb34Gnibi7qfe2x41D367vNNn/IGevXA3pW7uja2b7tj2M2fY94TPRpyv8bQEKW5wj5zLNlM9e979wy/LVqxa78/MqCZ5eN5eWDAZdYWF7evL8G31RcwM82yMJPhMzPkZW5hdw82ZqHZs3NE3UOm5ffWiGv8MOBbwW/2Nxczee3cit5Mk9b/wr/WtmcM0+wkM/Nz6krBDaTHNyWDdiUz23lCWyDrQsjAQQNrB6Q5dxqbmNzY2trc2t1YXVlNXeQ/xknUrzKLecP8APssrm6AJu35jbn5zfIVm51DrdvHGyRn1ZekYPc6srGVv5V/ieARpXPL5C5ObI8DyfALzZ8ywtbQyS0RvQQHpcXNrcO1J9XN+ZW1c2VlU2438vzB6ur+VXYLQ/qBZ1RwAMyt3lA8qsA+PMm2dxYxuYiCvy1SvAEGyS3BR+3FvDQzU0w0pUDNIatPOzhOyC51WECRmkm5wP/+wdYE9Vgfn5ubh6VuIL/2MD9UIMbeQqxDKzz81sAuJV7pf6cpx722RwYHuhHXUYNquDRP5H8P6iPfrY59yp/QGDLZ+TVys9wJ1eHqcG4JMRb2MPN0f+oC5FcPp/LoautzPt8dKqPOp8j9AP4EMnnyOYc7pPPr+Tmc4gxtwmagkNV8wRkASPSsnmNzU10PpKbz2NoAsC54fHRx9oZunzx/crqxjw56A8yRPucyHhLyqwFZrwjtMy1E9SoJHo6eZS7/e5J8dqpYx8lnjk+GdpKRk96NmQynfsyXUdaH1PdMxHpxkTvjmTAAKnP0aWeo28smWo1RYqxlkGKGZLcTCyQy3KaSTVIolXNkFixVSTJkmqkWymSm06nackiaT2trsZIxihP6yTZSqdJI5MxqlUdz5gk6mW1CBsP9bR5cnI5bdDW5lO0waVqWSeGMVtKw3DYMFpwRBqOuFTVaZIpEqNqEN04TBmkEWvBaD5WNW77Ez9LhNQJHF3NJA1S1RulZJqU8glDbRK9TqolUldLRXWNZKZJXScVOjfo08/xSGiwkcg0SSJNXqp6nBipTJ2UDDgd/Ekn4U/yUCVNUi6RQ70RIwa11sN/onHoLfwzm1I/VeHwGZ0sErgr5WQxkZjJx1LFBik29LiKTTNIOpUqk0b5KorBko9Uq3XdSEJbyaE6Sxolkm4aJBFLQXNmgZiUdQBsEbiz0HIsDRO1SSsk1Wq1VcykSaach4u3ABBueCadZ6vVQ/UQNs7DLYOD4Jh0Jg3mQevZxiKeIrEEO5FZlbyGI+BY2KkJBhnLXBqN0jRJt6ZbRb0KtwPbFUsUS0T/++0A1ToaupHBExXhXjdK4HFGMRHD0y+SaRewBcogS9g6yyPKGZJZoYAENBoxAUH/YBMN0gK7RZuAf1FA0EnDcI+F3WAnG7CJ9+FQJ7EGmS6rs2nUv57SyxQwCYAJOPEtNUiK1XKaXKYQUP+nDwDVQ2NWhzOmyy24Xh44AHCaqIckeVimz9Kt+pJ+aNR1BKyS0nS5iYCXJAU+N12+JJlD41CFs0Djp3MACC1vuQGVIen0dANMBggQsIwOe5ieBgVfkmaCnjqDt7hOb/wmSU+Xb11Xs+tM0GxaVWIINUMGN1goDKPCJ6aIzfWKbkZEhhSpL1P9qPYZ84T+QLJZqWIg82l4JrTRs+ORDONjAEM1T0eciE4jcoEh5n/QayWvme15M+kflkv1eu+kYLpr8fDwmmnY+dnZLhV4rjHdc0HGvHO2qNXZ6v30iGMlT/AFxIlMZCITmchEJjKRiUxkIhOZyL3KvwAPKGQoOiE1dQAAAABJRU5ErkJggg==" alt="Logo Tripatra" class="h-14 w-auto">
                    </div>

                    <span class="mb-3 text-4xl font-bold text-center text-gray-800 dark:text-gray-200">Selamat Datang</span>
                    <span class="font-light text-gray-500 dark:text-gray-400 mb-8 text-center">
                        Silakan masuk dengan akun Anda
                    </span>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Input Kode Karyawan / Email Admin -->
                        <div>
                            <x-input-label for="email" :value="__('ID Karyawan')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                            type="password"
                                            name="password"
                                            required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                        
                        <!-- Input Tersembunyi untuk Device ID -->
                        <input type="hidden" name="device_id" id="device_id" value="">

                        <div class="flex flex-col items-center mt-8">
                            <x-primary-button class="w-full justify-center py-3">
                                {{ __('Log in') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <!-- Kolom Gambar -->
                <div class="relative hidden md:block md:w-1/2">
                    <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=2070&auto=format&fit=crop"
                         alt="Ilustrasi Kantor"
                         class="w-full h-full rounded-r-2xl object-cover" />
                    <!-- Overlay -->
                    <div class="absolute top-0 left-0 w-full h-full bg-green-900 bg-opacity-30 rounded-r-2xl"></div>
                </div>
            </div>
        </div>

        <!-- Script untuk FingerprintJS (dijalankan di latar belakang) -->
        <script src="https://cdn.jsdelivr.net/npm/fingerprintjs2/dist/fingerprint2.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                if (window.requestIdleCallback) {
                    requestIdleCallback(function () {
                        Fingerprint2.get(function (components) {
                            const values = components.map(function (component) { return component.value });
                            const murmur = Fingerprint2.x64hash128(values.join(''), 31);
                            document.getElementById('device_id').value = murmur;
                        });
                    });
                } else {
                    setTimeout(function () {
                        Fingerprint2.get(function (components) {
                            const values = components.map(function (component) { return component.value });
                            const murmur = Fingerprint2.x64hash128(values.join(''), 31);
                            document.getElementById('device_id').value = murmur;
                        });
                    }, 500);
                }
            });
        </script>
    </body>
</html>

