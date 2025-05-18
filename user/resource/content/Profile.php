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
                <form id="form-info" class="profile-content">
                    <div class="profile-content-item">
                        <span>Fullname</span>
                        <input type="text" name="fullname"
                            data-old="<?= htmlspecialchars($user['fullname']) ?>"
                            value="<?= htmlspecialchars($user['fullname']) ?>"
                            placeholder="Fullname">
                    </div>
                    <div class="profile-content-item">
                        <span>Phone number</span>
                        <input type="phone" name="phone"
                            data-old="<?= htmlspecialchars($user['phone']) ?>"
                            value="<?= htmlspecialchars($user['phone']) ?>"
                            placeholder="Phone number">
                    </div>
                    <div class="profile-content-item">
                        <span>Address</span>
                        <input type="text" name="address"
                            data-old="<?= htmlspecialchars($user['address']) ?>"
                            value="<?= htmlspecialchars($user['address']) ?>"
                            placeholder="Address">
                    </div>
                    <button class="btnChange" type="submit" data-url="/ShoesStore/public/index.php?Page=Profile&action=updateInfo">Update info</button>
                </form>

                <!-- Password Section -->
                <form id=" form-password" class="profile-content" style="display: none;">
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