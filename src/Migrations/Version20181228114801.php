<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181228114801 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE post (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED NOT NULL COMMENT \'The post author\', title VARCHAR(255) NOT NULL COMMENT \'The post title\', slug VARCHAR(255) NOT NULL COMMENT \'The post slug\', content LONGTEXT DEFAULT NULL COMMENT \'The post body\', published_at DATETIME DEFAULT \'0000-00-00 00:00:00\' COMMENT \'The post publishig date\', thumbnail VARCHAR(2000) DEFAULT NULL COMMENT \'The post thumbnail URI\', created_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The record creation timestamp\', updated_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The last record update timestamp\', INDEX IDX_5A8A6C8DA76ED395 (user_id), INDEX IDX_5A8A6C8D2B36786B (title), INDEX IDX_5A8A6C8D989D9B62 (slug), INDEX IDX_5A8A6C8DE0D4FDE1 (published_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT UNSIGNED AUTO_INCREMENT NOT NULL, post_id INT UNSIGNED NOT NULL COMMENT \'The parent post\', user_id INT UNSIGNED NOT NULL COMMENT \'The comment author\', content LONGTEXT DEFAULT NULL COMMENT \'The comment body\', created_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The record creation timestamp\', updated_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The last record update timestamp\', INDEX IDX_9474526C4B89032C (post_id), INDEX IDX_9474526CA76ED395 (user_id), INDEX IDX_9474526C8B8E8428 (created_at), INDEX IDX_9474526C43625D9F (updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT UNSIGNED AUTO_INCREMENT NOT NULL, post_id INT UNSIGNED NOT NULL COMMENT \'The parent post\', slug VARCHAR(255) NOT NULL COMMENT \'The slug\', name VARCHAR(255) NOT NULL COMMENT \'The tag\', created_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The record creation timestamp\', updated_at DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL COMMENT \'The last record update timestamp\', INDEX IDX_389B7834B89032C (post_id), INDEX IDX_389B783989D9B62 (slug), INDEX IDX_389B7835E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT UNSIGNED AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7834B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7834B89032C');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CA76ED395');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
