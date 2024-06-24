<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inika:wght@400;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/global.css">
    <link rel="stylesheet" href="/assets/css/auth.css">
</head>
<body>
    <div class="container-fluid h-100">
        <?php require_once('./views/components/loading.php'); ?>
        <div class="row h-100">
            <div class="bubble-container d-none d-xl-block">
                <div class="bubble bubble-1"></div>
                <div class="bubble bubble-2"></div>
            </div>
            <div class="d-none d-xl-block col-12 col-xl-6 color-bg-green-1 h-100">
                <div class="d-flex h-100 justify-content-center align-items-center">
                    <div style="position: relative; z-index: 8;">
                        <h1 class="inika-regular text-white me-5 mb-0">PERPUS-KU</h1>
                    </div>
                    <div style="position: relative; z-index: 9;">
                        <svg width="280" height="215" viewBox="0 0 280 215" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_35_338)">
                                <path d="M261.057 184.544L261.535 173.783C266.525 171.116 272.09 169.707 277.747 169.679C269.961 176.045 270.934 188.316 265.655 196.876C263.988 199.534 261.744 201.782 259.091 203.454C256.437 205.127 253.44 206.18 250.324 206.537L243.808 210.527C242.914 205.542 243.111 200.424 244.386 195.523C245.661 190.622 247.983 186.056 251.192 182.139C253.014 179.959 255.104 178.018 257.411 176.36C258.972 180.475 261.057 184.544 261.057 184.544Z" fill="#F2F2F2"/>
                                <path d="M162.249 213.206C158.93 212.97 158.065 213.624 153.044 213.206H143.838L145.036 191.176L159.528 196.983L231.545 198.751L231.502 198.874C231.47 198.968 228.272 208.32 230.772 211.862L230.875 212.009L230.673 212.011L164.609 212.616C163.888 212.623 162.965 213.257 162.249 213.206Z" fill="#F2F2F2"/>
                                <path d="M165.984 213.494C159.574 213.038 152.935 215.53 147.395 212.716L142.966 212.985L142.953 212.947C142.919 212.845 140.827 196.384 145.056 190.001L145.243 189.72L145.388 191.232L143.994 211.738L147.084 210.707C154.589 214.239 163.515 211.906 172.106 211.874L230.566 211.658C231.79 211.654 232.81 212.49 232.843 213.523L232.846 213.624L169.516 213.619C168.335 213.619 167.158 213.577 165.984 213.494Z" fill="#CCCCCC"/>
                                <path d="M227.484 203.194L161.696 201.887L161.629 201.861L146.086 196.036L146.38 195.252L161.856 201.053L227.501 202.357L227.484 203.194Z" fill="#E6E6E6"/>
                                <path d="M227.133 206.717L161.345 205.409L161.278 205.384L145.735 199.559L146.028 198.775L161.505 204.576L227.15 205.88L227.133 206.717Z" fill="#E6E6E6"/>
                                <path d="M226.782 210.24L160.994 208.932L160.927 208.907L145.384 203.081L145.677 202.297L161.154 208.099L226.799 209.403L226.782 210.24Z" fill="#E6E6E6"/>
                                <path d="M166.439 198.979C160.339 198.567 154.392 196.889 148.977 194.052L144.417 191.633L144.536 190.437C144.569 190.262 144.666 190.105 144.808 189.997C144.95 189.888 145.127 189.836 145.305 189.851L145.708 189.856L207.061 190.759C211.076 190.809 215.055 191.531 218.832 192.895L232.2 197.748L232.193 197.819C232.081 198.945 230.945 199.816 229.607 199.8L169.172 199.092C168.258 199.081 167.347 199.044 166.439 198.979Z" fill="#E6E6E6"/>
                                <path d="M232.071 198.007L232.058 198.007L158.484 196.321L158.433 196.297L145.168 190.154L145.303 189.949L145.706 189.954L158.607 195.932L231.499 197.6L232.083 197.614L232.071 198.007Z" fill="#CCCCCC"/>
                                <path d="M174.875 190.384L173.941 190.371L152.855 190.059L151.856 190.045C156.674 195.174 167.326 196.457 182.94 194.47L174.875 190.384Z" fill="#CCCCCC"/>
                                <path d="M234.156 67.7071L235.443 70.6993L233.34 81.3252L211.894 78.3435L212.065 65.3861L212.496 62.5483L234.156 67.7071Z" fill="#FFB6B6"/>
                                <path d="M245.237 205.423H240.505L238.253 187.173H245.237V205.423Z" fill="#FFB6B6"/>
                                <path d="M231.163 211.6C231.163 212.136 231.376 212.65 231.755 213.029C232.134 213.408 232.648 213.621 233.184 213.621H242.186L243.737 210.418L244.34 213.621H247.733L246.779 202.181L245.237 202.09L240.055 201.782L239.22 201.73V204.971L232.036 209.934C231.766 210.121 231.546 210.369 231.394 210.659C231.242 210.95 231.163 211.272 231.163 211.6Z" fill="#2F2E41"/>
                                <path d="M201.504 184.502H196.772L193.989 157.634L202.879 157.103L201.504 184.502Z" fill="#FFB6B6"/>
                                <path d="M187.429 190.679C187.43 191.215 187.643 191.728 188.022 192.107C188.401 192.486 188.915 192.7 189.451 192.7H198.452L200.004 189.497L200.607 192.7H203.999L203.046 181.259L201.67 181.179L196.398 180.861L195.487 180.809V184.049L188.302 189.013C188.033 189.199 187.813 189.448 187.661 189.738C187.509 190.028 187.429 190.351 187.429 190.679Z" fill="#2F2E41"/>
                                <path d="M247.245 189.685H247.227L246.317 160.011C246.906 158.875 247.204 157.611 247.187 156.332C247.169 155.053 246.835 153.798 246.215 152.678L246.085 152.443L246.422 150.877C246.856 148.865 246.657 146.768 245.852 144.874L245.004 117.194C248.036 97.5386 233.583 84.7202 233.583 84.7202L234.911 74.4184L211.534 74.9497L211.931 84.7202L207.283 100.691C204.837 124.524 188.373 139.505 190.875 162.296C190.88 162.362 190.871 162.427 190.847 162.489C190.593 163.261 190.503 164.077 190.584 164.886C190.665 165.695 190.915 166.478 191.317 167.184C191.421 167.364 191.492 167.561 191.528 167.767C192.044 171.762 192.111 166.708 192.794 170.668L192.661 170.735C192.183 170.901 191.977 171.564 192.203 172.217C192.428 172.869 192.999 173.265 193.477 173.099L205.974 173.233C206.453 173.068 206.658 172.405 206.433 171.752C206.208 171.099 205.637 170.704 205.158 170.869L204.381 171.262L205.682 167.007L206.138 166.581C206.356 166.377 206.525 166.126 206.633 165.848C206.741 165.569 206.785 165.269 206.76 164.971C206.736 164.674 206.645 164.385 206.493 164.127C206.342 163.87 206.134 163.65 205.886 163.484L207.181 156.568L206.997 151.338L207.376 145.404L223.774 121.644L230.408 154.464L237.185 190.015C237.027 190.158 236.908 190.339 236.84 190.541C236.772 190.742 236.757 190.958 236.796 191.167C236.858 191.46 237.02 191.721 237.254 191.907C237.489 192.093 237.78 192.192 238.079 192.186H247.278C247.471 192.186 247.661 192.141 247.834 192.055C248.007 191.97 248.157 191.845 248.274 191.692C248.391 191.538 248.471 191.36 248.507 191.17C248.544 190.981 248.536 190.786 248.485 190.6C248.407 190.332 248.244 190.098 248.02 189.933C247.796 189.767 247.523 189.68 247.245 189.685Z" fill="#2F2E41"/>
                                <path d="M239.064 17.2142C239.065 19.1088 238.601 20.9745 237.712 22.6478C236.823 24.321 235.537 25.7504 233.967 26.8107C232.397 27.8709 230.591 28.5295 228.707 28.7287C226.823 28.9278 224.918 28.6614 223.161 27.9529C221.404 27.2443 219.848 26.1152 218.629 24.6648C217.41 23.2144 216.566 21.4869 216.171 19.634C215.775 17.7812 215.841 15.8596 216.361 14.0379C216.882 12.2163 217.842 10.5503 219.157 9.18619L219.212 9.13069C219.305 9.03343 219.397 8.93622 219.495 8.84359L219.496 8.84326L219.498 8.84225L219.499 8.84074L219.499 8.83896C219.632 8.74205 219.754 8.63192 219.865 8.51026C221.427 6.32689 223.199 4.4961 227.49 5.63986C233.668 7.28653 239.064 10.8205 239.064 17.2142Z" fill="#2F2E41"/>
                                <path d="M175.484 12.2593C175.458 12.8785 175.568 13.496 175.806 14.0682C176.044 14.6404 176.405 15.1534 176.863 15.5711C177.321 15.9888 177.865 16.301 178.456 16.4857C179.048 16.6705 179.673 16.7232 180.287 16.6403L210.137 41.1377L214.696 33.1239L183.921 11.5637C183.714 10.5411 183.138 9.63013 182.304 9.00353C181.469 8.37694 180.434 8.07821 179.394 8.16396C178.354 8.2497 177.381 8.71396 176.661 9.46876C175.94 10.2236 175.522 11.2164 175.484 12.2593Z" fill="#FFB6B6"/>
                                <path d="M222.741 48.3622C222.741 48.3622 222.732 47.48 217.986 47.5139C212.096 47.556 195.874 39.0997 197.293 31.1357C196.62 31.4401 195.864 31.5118 195.145 31.3393C194.426 31.1669 193.785 30.7602 193.323 30.1833L201.22 22.5798C203.698 22.7472 203.723 24.4055 203.511 25.3576L209.858 27.8796L226.388 37.3767L223.469 46.9163L222.741 48.3622Z" fill="white"/>
                                <path d="M224.638 29.5977C229.999 29.5977 234.344 25.2524 234.344 19.8921C234.344 14.5319 229.999 10.1866 224.638 10.1866C219.278 10.1866 214.933 14.5319 214.933 19.8921C214.933 25.2524 219.278 29.5977 224.638 29.5977Z" fill="#FFB7B7"/>
                                <path d="M211.299 68.1665C210.763 68.882 210.489 69.7601 210.524 70.6535C210.558 71.5468 210.899 72.4011 211.489 73.0731C211.494 73.3578 211.536 73.633 211.94 74.6532C211.993 74.7522 212.054 74.8459 212.125 74.9332C212.138 74.9545 212.152 74.9751 212.167 74.9949C212.592 75.5444 213.183 75.9421 213.852 76.129L231.618 81.0877L233.967 81.7426L234.38 81.8565L234.608 78.6487C235.136 78.2108 235.49 77.5983 235.606 76.922C235.721 76.2457 235.591 75.5503 235.239 74.9617L234.911 74.4207L236.819 69.2912L237.004 68.7834L241.868 55.6914L241.872 54.8183L241.882 53.9167L241.929 47.6246C241.932 45.6415 241.474 43.6848 240.593 41.9081C239.713 40.1314 238.432 38.583 236.852 37.3844C235.413 36.2848 233.754 35.508 231.988 35.1067L231.95 35.102L230.028 32.5158L222.232 33.294L221.587 34.6179L220.804 36.2123L215.152 39.5767L213.221 44.0656L211.916 47.1026C209.895 48.5546 208.689 50.4195 208.343 52.6403C207.603 57.357 210.905 62.0785 211.299 62.6194L211.399 68.0337L211.299 68.1665Z" fill="white"/>
                                <path d="M234.796 22.4365C234.708 22.4226 234.62 22.4041 234.532 22.3902C233.018 22.1217 231.504 21.8578 229.995 21.5846L229.856 18.1633L228.027 21.2281C223.856 22.6124 219.953 21.83 216.281 20.1031C215.07 19.5282 213.895 18.8804 212.763 18.1633C212.39 12.377 214.729 8.67274 219.082 9.06123C219.241 9.07542 219.356 8.91311 219.494 8.84359L219.496 8.84326L219.498 8.84225L219.499 8.84074L219.499 8.83896C219.735 8.72785 219.976 8.62138 220.217 8.52417C222.101 7.78759 224.157 7.60106 226.143 7.98624C228.13 8.37143 229.966 9.31252 231.439 10.7001C234.402 13.5289 235.736 18.2003 234.796 22.4365Z" fill="#2F2E41"/>
                                <path d="M234.532 22.3902C234.532 22.3902 231.657 11.6585 219.333 9.18156C219.319 9.17698 219.272 9.15844 219.212 9.1307C219.175 9.10752 219.129 9.08435 219.083 9.06124C219.221 8.98251 219.356 8.91311 219.495 8.8436L219.496 8.84327L219.498 8.84226L219.499 8.84074L219.499 8.83896C219.62 8.72785 219.74 8.61675 219.865 8.51027L220.217 8.52417C220.217 8.52417 232.583 6.5658 234.532 22.3902Z" fill="#FD6584"/>
                                <path d="M239.273 90.0404C239.045 90.2354 238.767 90.364 238.471 90.412C238.175 90.4599 237.871 90.4255 237.593 90.3123C237.039 90.0776 236.548 89.7181 236.157 89.262C234.56 87.5873 233.42 85.5315 232.844 83.2907L233.376 82.3436C233.167 81.1755 233.364 79.9711 233.933 78.9303C234.503 77.8894 235.412 77.0747 236.508 76.6214C237.132 76.3568 237.824 76.2966 238.484 76.4496C238.811 76.5309 239.111 76.6991 239.35 76.9365C239.59 77.1739 239.761 77.4716 239.846 77.7982C240.051 78.8115 239.207 79.7263 238.352 80.308C236.863 81.3186 235.142 81.9345 233.349 82.0979C235.542 83.3336 237.437 85.0343 238.902 87.0806C239.24 87.5075 239.49 87.9978 239.636 88.5227C239.706 88.7859 239.71 89.0625 239.647 89.3274C239.583 89.5924 239.455 89.8374 239.273 90.0404Z" fill="#2F2E41"/>
                                <path d="M116.593 213.966H279.478C279.617 213.966 279.749 213.911 279.847 213.813C279.945 213.715 280 213.582 280 213.444C280 213.306 279.945 213.173 279.847 213.075C279.749 212.977 279.617 212.922 279.478 212.922H116.593C116.455 212.922 116.322 212.977 116.224 213.075C116.126 213.173 116.071 213.306 116.071 213.444C116.071 213.582 116.126 213.715 116.224 213.813C116.322 213.911 116.455 213.966 116.593 213.966Z" fill="#CCCCCC"/>
                                <path d="M159.852 79.8228V80.0954C159.265 79.4079 158.667 78.7264 158.074 78.0448C157.825 77.7603 157.576 77.4759 157.327 77.1914C156.272 75.9942 155.211 74.801 154.145 73.6117C153.072 72.4264 151.999 71.241 150.915 70.0616C150.654 69.7772 150.393 69.4927 150.126 69.2082C149.913 68.9692 149.681 68.7472 149.433 68.5444C149.183 68.3361 148.895 68.1772 148.585 68.0762C148.229 67.9774 147.853 67.9734 147.495 68.0644C147.086 68.1592 146.695 68.3074 146.298 68.4318C145.889 68.5681 145.486 68.6985 145.077 68.8289C144.259 69.0956 143.447 69.3624 142.629 69.629C142.619 69.633 142.609 69.6351 142.598 69.635C142.587 69.6349 142.577 69.6327 142.567 69.6286C142.557 69.6245 142.548 69.6184 142.541 69.6109C142.533 69.6033 142.528 69.5943 142.524 69.5845L142.522 69.5816C142.518 69.5703 142.516 69.5582 142.516 69.5461C142.517 69.534 142.52 69.5221 142.526 69.5113C142.531 69.5006 142.539 69.4912 142.549 69.4839C142.559 69.4766 142.57 69.4715 142.582 69.469C142.783 69.4038 142.991 69.3386 143.198 69.2734C144.022 69.0008 144.84 68.7341 145.664 68.4675C146.072 68.3311 146.481 68.2007 146.89 68.0703C147.27 67.9263 147.67 67.8461 148.076 67.8333C148.738 67.8809 149.359 68.1723 149.818 68.6511C150.073 68.8882 150.304 69.1371 150.535 69.392C150.808 69.6883 151.081 69.9846 151.353 70.281C152.45 71.4782 153.538 72.6793 154.619 73.8843C155.703 75.0934 156.778 76.3044 157.843 77.5174C157.92 77.6063 157.997 77.6952 158.074 77.7841C158.673 78.4596 159.259 79.1413 159.852 79.8228Z" fill="white"/>
                                <path d="M149.341 69.6364C149.117 69.3479 148.819 69.1261 148.478 68.995L148.141 69.0068C150.786 73.1229 154.251 76.6494 158.321 79.366C155.327 76.1228 152.334 72.8796 149.341 69.6364Z" fill="white"/>
                                <path d="M204.544 112.412L191.117 40.5225C190.888 39.3375 190.262 38.2656 189.343 37.4827C188.425 36.6998 187.267 36.2523 186.06 36.2135C185.952 36.2111 185.843 36.2111 185.735 36.2135L176.588 36.5416L175.604 36.5778L171.039 36.7419L170.055 36.7757L167.119 36.8818L107.913 39.0074C107.823 39.0122 107.732 39.0122 107.642 39.0098L94.3752 38.7323L89.4654 38.631L89.3086 38.6286L85.6848 38.5514L54.1081 37.8951L45.7363 37.719L41.6082 37.6346L40.6191 37.6128L35.912 37.5163L34.9252 37.4946L30.1096 37.3957L29.1228 37.374L23.8319 37.263L23.0019 37.2461L18.2828 37.1472L17.5373 37.1327H17.4287C16.1759 37.1378 14.9652 37.5857 14.0107 38.3972C13.0562 39.2087 12.4193 40.3315 12.2126 41.5672L0.0697425 116.166C-0.0535672 116.922 -0.0111132 117.695 0.194154 118.433C0.399421 119.171 0.762587 119.855 1.25852 120.438C1.75445 121.022 2.37127 121.49 3.06626 121.811C3.76125 122.133 4.51779 122.299 5.28346 122.299H75.0185C76.0449 122.302 77.0437 122.631 77.8703 123.24L79.1924 124.22C80.1036 124.89 81.2046 125.254 82.3361 125.257C95.8045 121.702 109.965 121.702 123.433 125.257C124.127 125.259 124.815 125.123 125.457 124.857C126.098 124.591 126.681 124.201 127.17 123.708C128.007 122.871 129.125 122.374 130.307 122.311L199.627 118.658C200.378 118.619 201.113 118.42 201.781 118.073C202.45 117.727 203.036 117.242 203.502 116.65C203.968 116.059 204.301 115.375 204.481 114.644C204.661 113.913 204.682 113.152 204.544 112.412Z" fill="#CCCCCC"/>
                                <path d="M185.547 101.202C185.458 103.638 184.405 105.939 182.62 107.599C180.835 109.259 178.464 110.143 176.028 110.055C172.003 109.913 167.199 109.842 161.985 109.951C144.96 110.315 123.57 112.658 110.703 121.085C109.086 122.147 107.175 122.671 105.243 122.583C105.1 122.575 104.962 122.566 104.82 122.554C102.817 122.371 100.929 121.534 99.4476 120.173C92.3594 113.687 76.1036 105.834 38.893 105.281C38.7382 105.281 38.5876 105.273 38.4369 105.265C37.2317 105.189 36.0533 104.876 34.9689 104.345C33.8846 103.813 32.9157 103.073 32.1175 102.167C31.3193 101.261 30.7076 100.206 30.3172 99.0632C29.9268 97.9204 29.7655 96.712 29.8425 95.5068L33.4995 37.467L34.0476 28.7679L35.01 13.5498C35.1071 12.0172 35.7839 10.5791 36.9031 9.52753C38.0222 8.476 39.4997 7.88996 41.0354 7.88849H41.073L63.2412 8.0182C72.5805 8.44081 80.3158 9.63053 86.4471 11.5874C88.5414 12.2368 90.579 13.0569 92.5394 14.0394C95.2169 15.3375 97.6225 17.1339 99.6275 19.3325C100.876 20.737 102.58 21.6569 104.439 21.9309C104.728 21.9739 105.018 22.0005 105.31 22.0104C107.025 22.0813 108.717 21.5971 110.134 20.6296C124.616 10.9263 154.127 10.1898 170.425 10.6083C172.704 10.6614 174.882 11.5611 176.534 13.1321C178.187 14.7031 179.195 16.8328 179.363 19.1065L180.024 27.7637L180.681 36.3958L184.472 86.2847L185.526 100.164C185.552 100.509 185.559 100.856 185.547 101.202Z" fill="#F2F2F2"/>
                                <path d="M44.4081 28.3118C57.7672 24.0496 72.6711 25.178 85.327 31.1825C88.938 32.8869 92.3484 34.9874 95.4953 37.4454C96.3855 38.144 97.6523 36.8943 96.7526 36.1882C85.1283 27.1721 70.5228 22.889 55.8703 24.1995C51.8166 24.5623 47.8146 25.3663 43.9354 26.5973C42.8494 26.9437 43.3136 28.661 44.4081 28.3118Z" fill="#3F3D56"/>
                                <path d="M117.919 38.1351C130.339 31.6068 145.212 30.1831 158.717 33.8889C162.567 34.9429 166.287 36.4222 169.81 38.2996C170.819 38.8381 171.718 37.3038 170.707 36.7644C157.837 29.8967 142.387 28.3157 128.332 32.1441C124.411 33.2139 120.618 34.708 117.021 36.5999C116.008 37.1325 116.906 38.6676 117.919 38.1351Z" fill="#3F3D56"/>
                                <path d="M117.919 49.9883C130.339 43.46 145.212 42.0364 158.717 45.7421C162.567 46.7961 166.287 48.2755 169.81 50.1529C170.819 50.6913 171.718 49.1571 170.707 48.6176C157.837 41.7499 142.387 40.1689 128.332 43.9973C124.411 45.0672 120.618 46.5613 117.021 48.4531C116.008 48.9856 116.906 50.5208 117.919 49.9883Z" fill="#3F3D56"/>
                                <path d="M171.142 61.2057C171.152 61.0531 171.115 60.9012 171.037 60.7698C170.959 60.6384 170.843 60.5335 170.704 60.4688C160.394 55.0698 148.703 52.88 137.137 54.1813C137.065 54.1871 136.998 54.1987 136.92 54.2045H136.917C136.846 54.2109 136.775 54.2216 136.705 54.2364C129.826 55.028 123.153 57.0849 117.022 60.3034C116.888 60.383 116.777 60.496 116.7 60.6313C116.624 60.7666 116.583 60.9196 116.583 61.0752V83.1872C116.586 83.3233 116.619 83.457 116.68 83.5784C116.742 83.6998 116.83 83.8056 116.939 83.8878C117.047 83.97 117.173 84.0264 117.306 84.0528C117.44 84.0792 117.578 84.0749 117.709 84.0402C117.982 83.9619 118.255 83.8864 118.533 83.8139C124.731 82.1362 131.094 81.1409 137.509 80.8457C137.645 80.8399 137.782 80.8341 137.924 80.8283C143.148 80.6039 148.382 80.7921 153.577 81.3911C153.686 81.3973 153.794 81.4089 153.902 81.4259C154.169 81.455 154.442 81.4869 154.709 81.5217C154.816 81.5333 154.918 81.5449 155.016 81.5623C156.055 81.6929 157.085 81.8408 158.118 82.0004C159.609 82.2384 161.1 82.5034 162.592 82.7955C162.685 82.8129 162.78 82.8361 162.876 82.8535C165.27 83.3293 167.652 83.8806 170.022 84.4899C170.154 84.524 170.293 84.5276 170.426 84.5006C170.56 84.4735 170.686 84.4165 170.794 84.3338C170.902 84.2512 170.991 84.1449 171.052 84.0232C171.113 83.9015 171.146 83.7674 171.148 83.6311V61.2405C171.149 61.2286 171.147 61.2167 171.142 61.2057Z" fill="#3F3D56"/>
                                <path d="M138.531 55.387C137.88 55.068 137.133 55.0024 136.436 55.203C136.128 55.3184 135.847 55.4939 135.607 55.7192C135.367 55.9444 135.175 56.215 135.041 56.5153C134.799 57.091 134.645 57.6993 134.582 58.3204C134.545 58.5647 134.542 58.873 134.788 58.9871C134.877 59.0194 134.973 59.0268 135.066 59.0084C135.16 58.99 135.246 58.9465 135.316 58.8826C135.443 58.7576 135.547 58.6118 135.625 58.4515C136.051 57.7881 136.638 57.2428 137.331 56.8662C138.024 56.4895 138.8 56.2935 139.589 56.2962L140.245 56.5579C139.729 56.0927 139.152 55.699 138.531 55.387Z" fill="#FF6582"/>
                                <path d="M145.636 60.4835C145.126 59.7336 144.523 58.9329 143.577 58.6881C142.483 58.4045 141.358 58.9678 140.404 59.5237C137.567 61.1757 134.821 62.9771 132.175 64.9205L132.178 64.9496L138.319 64.5726C139.797 64.4819 141.327 64.3786 142.634 63.7559C143.103 63.4617 143.628 63.2664 144.175 63.182C144.515 63.2004 144.848 63.2858 145.154 63.4335C145.46 63.5811 145.735 63.788 145.961 64.0421C148.791 66.7245 149.61 70.8265 152.784 73.1831C150.764 68.7558 148.373 64.5074 145.636 60.4835Z" fill="white"/>
                                <path d="M137.925 80.8279C137.783 80.8338 137.646 80.8398 137.51 80.8457C136.52 80.4901 135.507 80.176 134.481 79.9033C133.818 79.7255 133.142 79.5655 132.466 79.4292C131.702 79.2633 130.931 79.1329 130.161 79.0203C128.694 78.8069 127.216 78.6823 125.734 78.6469C125.722 78.6482 125.711 78.647 125.7 78.6435C125.69 78.6401 125.68 78.6344 125.671 78.6268C125.663 78.6193 125.656 78.61 125.652 78.5997C125.647 78.5894 125.645 78.5782 125.645 78.5669C125.645 78.5556 125.647 78.5445 125.652 78.5341C125.656 78.5238 125.663 78.5146 125.671 78.507C125.68 78.4995 125.69 78.4938 125.7 78.4903C125.711 78.4869 125.722 78.4857 125.734 78.4869C125.923 78.4869 126.113 78.4929 126.297 78.5047C127.782 78.5596 129.262 78.7041 130.73 78.9374C131.494 79.0618 132.259 79.21 133.018 79.3759C133.705 79.53 134.392 79.7019 135.068 79.8915C136.034 80.1641 136.986 80.4763 137.925 80.8279Z" fill="white"/>
                                <path d="M155.017 81.5628C154.916 81.545 154.816 81.5331 154.709 81.5213C154.3 81.1835 153.885 80.8635 153.453 80.5494C152.913 80.1582 152.356 79.7907 151.787 79.447C150.527 78.6821 149.212 78.0125 147.852 77.4438C145.702 76.5485 143.477 75.8464 141.202 75.3458C138.608 74.7774 135.982 74.3657 133.338 74.113C130.694 73.8404 128.039 73.6745 125.384 73.5085C125.271 73.5026 125.266 73.3367 125.384 73.3426C125.716 73.3663 126.048 73.384 126.386 73.4077C129.07 73.5737 131.755 73.7634 134.422 74.0657C137.067 74.3491 139.69 74.8044 142.275 75.4287C144.482 75.9726 146.636 76.7124 148.711 77.6394C149.905 78.1757 151.061 78.7915 152.172 79.4825C152.878 79.927 153.559 80.4012 154.211 80.9049C154.484 81.1183 154.756 81.3317 155.017 81.5628Z" fill="white"/>
                                <path d="M153.903 81.4265C153.795 81.4091 153.686 81.3972 153.577 81.3909C153.453 81.2902 153.322 81.1954 153.192 81.1005C152.279 80.4474 151.327 79.8516 150.341 79.3166C149.445 78.8298 148.525 78.3887 147.585 77.995C145.436 77.0971 143.211 76.3949 140.935 75.897C138.341 75.3286 135.715 74.9169 133.071 74.6643C130.428 74.3916 127.772 74.2257 125.117 74.0597C125.005 74.0479 125.005 73.8878 125.117 73.8938C125.455 73.9175 125.787 73.9352 126.119 73.959C128.804 74.1249 131.488 74.3146 134.155 74.6168C136.8 74.9002 139.423 75.3556 142.008 75.98C144.215 76.5228 146.369 77.2627 148.444 78.1906C149.227 78.5462 149.991 78.9314 150.738 79.3522C151.845 79.9602 152.904 80.6537 153.903 81.4265Z" fill="white"/>
                                <path d="M162.876 82.8548C162.781 82.837 162.686 82.8134 162.591 82.7956C162.485 82.6829 162.384 82.5644 162.283 82.4459C161.774 81.8591 161.27 81.2665 160.772 80.6679C158.81 78.3328 156.949 75.9266 155.195 73.473C153.69 71.3749 152.261 69.2354 150.91 67.0544C150.264 66.0232 149.642 64.9801 149.031 63.9251C148.557 63.1073 148.095 62.2875 147.644 61.4656C147.514 61.2286 147.39 60.9915 147.265 60.7544C146.975 60.1973 146.684 59.6343 146.376 59.0831C146.041 58.4078 145.549 57.8226 144.942 57.3762C144.638 57.1729 144.287 57.0524 143.922 57.0266C143.409 57.0285 142.903 57.1589 142.453 57.4058C139.137 58.95 136.009 60.8689 133.13 63.125C130.318 65.312 127.776 67.8246 125.556 70.6104C125.485 70.6934 125.331 70.6163 125.396 70.5274C125.663 70.1955 125.935 69.8636 126.208 69.5376C128.341 67.0139 130.744 64.7305 133.373 62.728C133.379 62.722 133.391 62.7161 133.397 62.7102C133.628 62.5265 133.859 62.3487 134.096 62.1768C135.564 61.096 137.092 60.0987 138.672 59.1898C139.478 58.7216 140.301 58.2771 141.143 57.8563C141.35 57.7497 141.564 57.643 141.777 57.5422C141.99 57.4355 142.204 57.3348 142.417 57.234C142.839 57.0186 143.301 56.8916 143.774 56.8607C145.422 56.8369 146.269 58.4964 146.856 59.6165C147.04 59.9662 147.23 60.3159 147.413 60.6655C148.125 61.9871 148.865 63.3029 149.636 64.6008C150.104 65.4068 150.59 66.207 151.082 67.0011C151.787 68.1331 152.51 69.2532 153.263 70.3675C153.263 70.3682 153.263 70.369 153.263 70.3697C153.264 70.3704 153.264 70.3711 153.264 70.3716C153.265 70.3722 153.266 70.3726 153.266 70.3729C153.267 70.3732 153.268 70.3734 153.269 70.3734C154.14 71.6891 155.047 72.9811 155.983 74.2672C157.589 76.4659 159.273 78.6153 161.033 80.7154C161.637 81.4384 162.252 82.1515 162.876 82.8548Z" fill="white"/>
                                <path d="M141.731 56.68L141.568 56.7517C141.192 56.1176 140.666 55.5861 140.035 55.2044C139.405 54.8227 138.69 54.6027 137.954 54.5639C135.527 54.3916 133.396 56.0086 133.202 58.1683C133.124 59.0575 133.381 59.944 133.924 60.6528L133.772 60.7428C133.206 60.0046 132.938 59.0814 133.02 58.1553C133.222 55.9063 135.442 54.2224 137.968 54.4018C138.735 54.4422 139.479 54.6713 140.136 55.0688C140.792 55.4662 141.34 56.0197 141.731 56.68Z" fill="#FF6582"/>
                                <path d="M129.742 61.0495C129.105 60.7726 128.412 60.6511 127.719 60.6952C127.026 60.7394 126.353 60.9478 125.756 61.3032C127.934 61.6374 130.093 62.0858 132.224 62.6468C131.342 62.1866 130.651 61.4656 129.742 61.0495Z" fill="white"/>
                                <path d="M125.744 61.3013L125.504 61.4748C125.586 61.414 125.67 61.3571 125.756 61.3032L125.744 61.3013Z" fill="white"/>
                                <path d="M132.966 62.3075C132.87 62.2126 132.775 62.1178 132.679 62.023C131.941 61.2201 131.053 60.5701 130.064 60.1098C129.561 59.8978 129.021 59.7864 128.475 59.7819C127.911 59.795 127.353 59.9033 126.825 60.1022C126.577 60.1905 126.334 60.2908 126.095 60.3976C125.821 60.5201 125.553 60.6507 125.285 60.7824C124.784 61.0295 124.288 61.2875 123.8 61.5563C122.827 62.0913 121.885 62.667 120.974 63.2836C120.501 63.6034 120.038 63.9336 119.585 64.2744C119.164 64.5909 118.751 64.9159 118.347 65.2494C118.261 65.3204 118.131 65.2055 118.218 65.1345C118.324 65.0461 118.432 64.9583 118.54 64.8716C118.844 64.6266 119.154 64.3864 119.468 64.1508C120.041 63.7204 120.629 63.3067 121.231 62.9097C122.168 62.2918 123.138 61.7159 124.139 61.1818C124.64 60.9152 125.147 60.6597 125.662 60.4155C125.817 60.3418 125.974 60.2697 126.133 60.2014C126.496 60.0386 126.87 59.9029 127.253 59.7954C127.803 59.638 128.377 59.5878 128.945 59.6475C129.49 59.7169 130.018 59.8795 130.507 60.1282C131.476 60.6713 132.35 61.3683 133.095 62.1921C133.173 62.2701 133.044 62.3855 132.966 62.3075Z" fill="white"/>
                                <path d="M44.4081 40.1651C57.7672 35.9029 72.6711 37.0313 85.327 43.0358C88.938 44.7402 92.3484 46.8407 95.4953 49.2987C96.3855 49.9973 97.6523 48.7476 96.7526 48.0414C85.1283 39.0253 70.5228 34.7423 55.8703 36.0528C51.8166 36.4155 47.8146 37.2195 43.9354 38.4506C42.8494 38.797 43.3136 40.5143 44.4081 40.1651Z" fill="#3F3D56"/>
                                <path d="M44.4081 52.0183C57.7672 47.7561 72.6711 48.8845 85.327 54.889C88.938 56.5934 92.3484 58.6939 95.4953 61.1519C96.3855 61.8505 97.6523 60.6008 96.7526 59.8946C85.1283 50.8785 70.5228 46.5955 55.8703 47.906C51.8166 48.2687 47.8146 49.0728 43.9354 50.3038C42.8494 50.6502 43.3136 52.3675 44.4081 52.0183Z" fill="#3F3D56"/>
                                <path d="M44.4081 63.8715C57.7672 59.6093 72.6711 60.7377 85.327 66.7423C88.938 68.4467 92.3484 70.5472 95.4953 73.0051C96.3855 73.7038 97.6523 72.4541 96.7526 71.7479C85.1283 62.7318 70.5228 58.4488 55.8703 59.7593C51.8166 60.122 47.8146 60.926 43.9354 62.157C42.8494 62.5035 43.3136 64.2207 44.4081 63.8715Z" fill="#3F3D56"/>
                                <path d="M44.4081 75.7248C57.7672 71.4626 72.6711 72.591 85.327 78.5956C88.938 80.2999 92.3484 82.4004 95.4953 84.8584C96.3855 85.5571 97.6523 84.3073 96.7526 83.6012C85.1283 74.5851 70.5228 70.302 55.8703 71.6125C51.8166 71.9753 47.8146 72.7793 43.9354 74.0103C42.8494 74.3568 43.3136 76.074 44.4081 75.7248Z" fill="#3F3D56"/>
                                <path d="M44.4081 87.5781C57.7672 83.3159 72.6711 84.4443 85.327 90.4488C88.938 92.1532 92.3484 94.2537 95.4953 96.7117C96.3855 97.4103 97.6523 96.1606 96.7526 95.4544C85.1283 86.4384 70.5228 82.1553 55.8703 83.4658C51.8166 83.8286 47.8146 84.6326 43.9354 85.8636C42.8494 86.21 43.3136 87.9273 44.4081 87.5781Z" fill="#3F3D56"/>
                                <path d="M92.5394 14.0394V42.463C92.5394 43.0279 91.5518 43.5007 90.2213 43.6304C90.21 43.6298 90.1986 43.6312 90.1878 43.6346C89.966 43.6514 89.7317 43.6639 89.4932 43.6639C88.7596 43.6774 88.0296 43.5583 87.3383 43.3124C87.2756 43.2873 87.2128 43.258 87.15 43.2329C87.0915 43.1995 87.0287 43.1702 86.9701 43.1367C86.6396 42.94 86.4471 42.7099 86.4471 42.463V11.5874C88.5414 12.2369 90.579 13.0569 92.5394 14.0394Z" fill="white"/>
                                <path d="M105.309 20.6318H104.438V121.989H105.309V20.6318Z" fill="#E6E6E6"/>
                                <path d="M199.966 90.0178C200.583 89.9614 201.18 89.7703 201.716 89.458C202.251 89.1457 202.712 88.7198 203.065 88.2104C203.418 87.7009 203.655 87.1202 203.759 86.5093C203.863 85.8983 203.832 85.2719 203.668 84.6741L221.735 76.2406L232.612 58.6462L224.062 55.1945L216.725 69.3725L198.153 81.7483C197.167 82.0904 196.341 82.7822 195.831 83.6926C195.321 84.6031 195.163 85.669 195.386 86.6883C195.61 87.7076 196.2 88.6095 197.044 89.2232C197.888 89.8369 198.927 90.1196 199.966 90.0178Z" fill="#FFB6B6"/>
                                <path d="M239.321 41.7912C239.321 41.7912 241.45 42.4863 239.113 46.6162C237.243 49.9226 228.129 68.9675 224.409 72.707C224.8 73.3341 224.972 74.0737 224.897 74.8091C224.822 75.5445 224.504 76.2341 223.994 76.769L215.406 69.9546C215.242 67.4762 216.882 67.2303 217.854 67.3139L219.83 60.3122L226.72 43.0401L237.791 41.2615L239.321 41.7912Z" fill="white"/>
                                <path d="M239.507 9.418C239.913 7.77643 242.858 6.56434 237.813 4.2725C236.588 3.71629 236.639 1.80082 235.603 0.943007C234.567 0.0851923 233.05 -0.312501 231.846 0.287078C230.771 0.8225 230.166 2.03418 230.036 3.22824C229.975 4.43003 230.113 5.63373 230.447 6.78991L230.349 6.93282C232.348 8.46221 234.347 9.99157 236.345 11.5209C237.08 12.0829 238.04 12.6714 238.863 12.2497C239.795 11.7723 239.255 10.4343 239.507 9.418Z" fill="#2F2E41"/>
                            </g>
                            <defs>
                                <clipPath id="clip0_35_338">
                                    <rect width="280" height="214.078" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-6 h-100 py-0 h-100" style="overflow-y: auto;">
                <div class="w-100 px-5 py-0 h-100">
                    <div class="d-flex align-items-center w-100 h-100">
                        <div class="w-100">
                            <h3 class="text-center inika-regular color-green-1">Reset Password</h3>
                            <form action="#" method="POST" id="formResetPassword">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label auth-form-label color-gray-1 inika-regular">Email</label>
                                    <input type="email" class="form-control poppins-regular" id="email" name="email" placeholder="Masukkan email anda" value="<?= $data['email'] ?>" disabled>
                                    <div class="mt-2">
                                        <span class="text-danger error" id="emailError"></span>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label auth-form-label color-gray-1 inika-regular">Password</label>
                                    <input type="password" class="form-control poppins-regular" id="password" name="password" placeholder="Masukkan password baru anda">
                                    <div class="mt-2">
                                        <span class="text-danger error" id="passwordError"></span>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="konfirmasiPassword" class="form-label auth-form-label color-gray-1 inika-regular">Konfirmasi</label>
                                    <input type="password" class="form-control poppins-regular" id="konfirmasiPassword" name="konfirmasiPassword" placeholder="Konfirmasi Password">
                                    <div class="mt-2">
                                        <span class="text-danger error" id="konfirmasiPasswordError"></span>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <button type="submit"  class="color-bg-green-1 btn text-white rounded" style="border-radius: 15px !important;">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/plugins/jquery/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="/assets/js/sweetalert2.js"></script>
    <script src="/assets/js/function.js"></script>
    <script src="/assets/js/request.js"></script>
    <script src="/assets/js/validator.js"></script>
    <script>
        // let resetStatus = false;
        // Handling resetPassword
        async function resetPassword(e) {
            e.preventDefault();
            // if(resetStatus) return;
            clearError();
            let request = new Request();
            let email = document.querySelector("#email").value;
            let password = document.querySelector("#password").value;
            let konfirmasiPassword = document.querySelector("#konfirmasiPassword").value;
            let validator = new Validator();
            let dataValidate = {
                'email': 'required|validEmail',
                'password': 'required',
                'konfirmasiPassword': 'required|matches[password]',
            };
            let data = {
                'email': email,
                'password': password,
                'konfirmasiPassword': konfirmasiPassword
            };
            validator.setInputName({
                'email': "Email",
                'password': "Password",
                'konfirmasiPassword': "Konfirmasi Password",
            })
            let validate = validator.validate(dataValidate, data);
            if(!validate) {
                let message = validator.getMessages()
                Object.keys(message).forEach((key) => {
                    Object.keys(message[key]).forEach((error_key) => {
                        document.querySelector(`#${key}Error`).innerText = message[key][error_key]
                    })
                })
                return
            }
            
            let formData = new FormData();
            formData.append('password', password);
            formData.append('konfirmasiPassword', konfirmasiPassword);
            var response;
            showLoading();
            try {
                request.setUrl('/auth/update-reset-password/<?= $data['id'] ?>?token=<?= $data['reset_token'] ?>').setMethod('PUT').setData(formData);
                response = await request.makeFormRequest();
                hideLoading()
                if(response['code'] == 200) {
                    // resetStatus = true;
                    showToast(response['message'], 'success', () => {
                        window.location.href = '/auth/login'
                    });
                }
                else {
                    showAlert(response['message'], 'warning');
                }
            } catch (error) {
                hideLoading();
                showAlert(response['message'], 'error')
            }
        }
        document.getElementById("formResetPassword").addEventListener('submit', resetPassword);
        $(window).on('ready', function() {
            $('input.form-control').on('keydown', (event) => {
                if(event.key == 'Enter') {
                    resetPassword();
                }
            })
        })
    </script>
</body>
</html>