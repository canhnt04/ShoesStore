<?php
require_once __DIR__ . "/../../../config/init.php";
?>

<div id="ajaxLoad">
    <div class="profile">
        <h2>Setting profile</h2>

        <div class="profile-body">
            <!-- Sidebar -->
            <div class="profile-nav">
                <!-- Avatar + Tên -->
                <div class="profile-avatar">
                    <div class="item-img">
                        <img src="/ShoesStore/public/assets/images/avatar_default.jpg" alt="Avatar">
                    </div>
                    <div class="item-username">
                        <h4><?php echo htmlspecialchars($user['username']) ?></h4>
                    </div>
                </div>

                <!-- Nút điều hướng -->
                <div class="profile-links">
                    <a href="#" id="btn-info" class="active">Info</a>
                    <a href="#" id="btn-password">Password</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="profile-main">
                <!-- Info Section -->
                <form id="form-info" class="profile-content" data-url="index.php?Page=Profile&action=updateInfo">
                    <div class="profile-content-item">
                        <span>Fullname</span>
                        <input type="text" name="fullname"
                            value="<?= htmlspecialchars($user['fullname']) ?>"
                            data-old="<?= htmlspecialchars($user['fullname']) ?>"
                            placeholder="Fullname">
                    </div>
                    <div class="profile-content-item">
                        <span>Phone number</span>
                        <input type="phone" name="phone"
                            value="<?= htmlspecialchars($user['phone']) ?>"
                            data-old="<?= htmlspecialchars($user['phone']) ?>"
                            placeholder="Phone number">
                    </div>
                    <div class="profile-content-item">
                        <span>Address</span>
                        <input type="text" name="address"
                            value="<?= htmlspecialchars($user['address']) ?>"
                            data-old="<?= htmlspecialchars($user['address']) ?>"
                            placeholder="Address">
                    </div>
                    <button class="btnChange" type="submit">Update info</button>
                </form>

                <!-- Password Section -->
                <form id="form-password" class="profile-content" style="display: none;">
                    <div class="profile-content-item">
                        <span>Old password</span>
                        <input type="password" name="old_password" required>
                    </div>
                    <div class="profile-content-item">
                        <span>New password</span>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="profile-content-item">
                        <span>Confirm new password</span>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button class="btnChange" type="submit">Change password</button>
                </form>
            </div>
        </div>
    </div>
</div>