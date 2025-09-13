<?php 
// Template Name: Chat Dashboard

if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
    exit;
}

$current_user_id = get_current_user_id();
$current_user = get_userdata($current_user_id);
$current_first_name = get_user_meta($current_user_id, 'first_name', true);
$current_last_name = get_user_meta($current_user_id, 'last_name', true);
$current_full_name = trim($current_first_name . ' ' . $current_last_name);
$current_avatar_id = get_user_meta($current_user_id, 'custom_profile_image', true);
$current_profile_img = $current_avatar_id ? wp_get_attachment_url($current_avatar_id) : get_avatar_url($current_user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chat Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css">
    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.10.1/firebase-database.js"></script>
    <style>
        .default-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            font-size: 1.5em;
            color: #666;
            text-align: center;
            padding: 20px;
        }
        .msg-header, .chat-page {
            display: none; /* Hide header and chat page by default */
        }
        .msg-page {
            height: 400px; /* Ensure container has height for centering */
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <!-- Main container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>Chats <?php echo "    Hi! ".$current_first_name.""; ?></h3>
            </div>
            <div class="user-list">
                <?php 
                $args = array(
                    'role'    => 'user',
                    'order'   => 'ASC'
                );
                $users = get_users( $args );
                foreach($users as $user){
                    if($user->ID != $current_user_id){
                        $first_name = get_user_meta($user->ID, 'first_name', true);
                        $last_name  = get_user_meta($user->ID, 'last_name', true);
                        $full_name  = trim($first_name . ' ' . $last_name);
                        $avatar_id   = get_user_meta($user->ID, 'custom_profile_image', true);
                        $profile_img = $avatar_id ? wp_get_attachment_url($avatar_id) : get_avatar_url($user->ID);
                        $user_data = get_userdata($user->ID);
                        $username = $user_data->user_login;
                ?>
                <div class="user-profile" data-user-id="<?php echo $user->ID; ?>" data-user-name="<?php echo esc_attr($full_name); ?>" data-user-img="<?php echo esc_attr($profile_img); ?>">
                    <img src="<?php echo esc_url($profile_img); ?>" class="user-img" alt="<?php echo esc_attr($full_name); ?>" />
                    <div class="user-info">
                        <p><?php echo esc_html($full_name); ?></p>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>

        <!-- Chat container -->
        <div class="chat-container">
            <!-- msg-header section -->
            <div class="msg-header">
                <div class="container1">
                    <img id="chat-user-img" src="" class="msgimg" alt="" />
                    <div class="active">
                        <p id="chat-user-name"></p>
                    </div>
                </div>
            </div>

            <!-- Chat inbox -->
            <div class="chat-page">
                <div class="msg-inbox">
                    <div class="chats">
                        <!-- Message container -->
                        <div class="msg-page" id="chat-messages">
                            <!-- Default message -->
                            <div class="default-message" id="default-message">
                                SMART CHAT. Select friends and make your community.
                            </div>
                        </div>
                    </div>

                    <!-- msg-bottom section -->
                    <div class="msg-bottom">
                        <div class="input-group">
                            <input type="text" id="message-input" class="form-control" placeholder="Write message..." />
                            <span class="input-group-text send-icon" id="send-button">
                                <i class="bi bi-send"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Firebase Configuration - Replace with your own from Firebase Console
    const firebaseConfig = {
            apiKey: "YOUR_API_KEY",
            authDomain: "YOUR_PROJECT_ID.firebaseapp.com",
            databaseURL: "https://YOUR_PROJECT_ID-default-rtdb.firebaseio.com",  // Use Realtime Database URL
            projectId: "YOUR_PROJECT_ID",
            storageBucket: "YOUR_PROJECT_ID.appspot.com",
            messagingSenderId: "YOUR_SENDER_ID",
            appId: "YOUR_APP_ID"
        };

    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    const db = firebase.database();

    // Current user details
    const currentUserId = <?php echo $current_user_id; ?>;
    const currentUserImg = "<?php echo esc_url($current_profile_img); ?>";

    let selectedUserId = null;
    let selectedUserImg = null;
    let chatListenerRef = null;

    function showDefaultState() {
        document.querySelector('.msg-header').style.display = 'none';
        document.querySelector('.chat-page').style.display = 'none';

        const msgPage = document.getElementById('chat-messages');
        msgPage.innerHTML = `
            <div class="default-message" id="default-message">
                SMART CHAT. Select friends and make your community.
            </div>
        `;

        // detach old listener
        if (chatListenerRef) {
            chatListenerRef.off();
            chatListenerRef = null;
        }
    }
    showDefaultState();

    // Handle user selection
    document.querySelectorAll('.user-profile').forEach(profile => {
        profile.addEventListener('click', () => {
            document.querySelectorAll('.user-profile').forEach(p => p.classList.remove('active'));
            profile.classList.add('active');

            document.querySelector('.msg-header').style.display = 'block';
            document.querySelector('.chat-page').style.display = 'block';

            selectedUserId = profile.getAttribute('data-user-id');
            const userName = profile.getAttribute('data-user-name');
            selectedUserImg = profile.getAttribute('data-user-img');

            document.getElementById('chat-user-name').textContent = userName;
            document.getElementById('chat-user-img').src = selectedUserImg;
            document.getElementById('chat-user-img').alt = userName;

            const msgPage = document.getElementById('chat-messages');
            msgPage.innerHTML = '';

            // detach old listener
            if (chatListenerRef) {
                chatListenerRef.off();
                chatListenerRef = null;
            }

            // generate chatId
            const chatParticipants = [currentUserId, selectedUserId].sort();
            const chatId = chatParticipants.join('_');

            // new ref
            chatListenerRef = db.ref('chats/' + chatId);

            // listen for messages
            chatListenerRef.orderByChild('timestamp').on('child_added', snapshot => {
                const msg = snapshot.val();
                const type = (msg.sender == currentUserId) ? 'outgoing' : 'received';
                const senderImg = (type === 'outgoing') ? currentUserImg : selectedUserImg;
                appendMessage(type, senderImg, msg.message, formatTimestamp(msg.timestamp));
            });
        });
    });

    // Send message
    const input = document.getElementById('message-input');
    const sendBtn = document.getElementById('send-button');
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });

    function sendMessage() {
        const message = input.value.trim();
        if (message && selectedUserId) {
            const chatParticipants = [currentUserId, selectedUserId].sort();
            const chatId = chatParticipants.join('_');
            db.ref('chats/' + chatId).push({
                sender: currentUserId,
                message: message,
                timestamp: firebase.database.ServerValue.TIMESTAMP
            });
            input.value = '';
        }
    }

    // Append message
    function appendMessage(type, senderImg, message, time) {
        const chatsDiv = document.createElement('div');
        chatsDiv.className = type + '-chats';

        const imgDiv = document.createElement('div');
        imgDiv.className = type + '-chats-img';
        const img = document.createElement('img');
        img.src = senderImg;
        img.alt = '';
        imgDiv.appendChild(img);

        const msgDiv = document.createElement('div');
        msgDiv.className = type + '-msg';

        const inboxDiv = document.createElement('div');
        inboxDiv.className = type + '-chats-msg';

        const p = document.createElement('p');
        p.textContent = message;

        const span = document.createElement('span');
        span.className = 'time';
        span.textContent = time;

        inboxDiv.appendChild(p);
        inboxDiv.appendChild(span);
        msgDiv.appendChild(inboxDiv);

        chatsDiv.appendChild(imgDiv);
        chatsDiv.appendChild(msgDiv);

        document.getElementById('chat-messages').appendChild(chatsDiv);

        // auto-scroll
        const msgPage = document.getElementById('chat-messages');
        msgPage.scrollTop = msgPage.scrollHeight;
    }

    // Format timestamp
    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'short' });
        return `${hours}:${minutes} | ${month} ${day}`;
    }
</script>

   
</body>
</html>