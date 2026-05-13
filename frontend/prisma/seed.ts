import { PrismaClient } from '@prisma/client'

const prisma = new PrismaClient()

async function main() {
  console.log('Seeding data...')

  // Seed Services
  await prisma.service.createMany({
    data: [
      { title: 'Web Development', description: 'Creating modern and responsive websites.' },
      { title: 'Mobile Apps', description: 'Building cross-platform mobile applications.' },
      { title: 'Digital Marketing', description: 'Helping you reach your target audience.' },
    ],
  })

  // Seed Projects
  await prisma.project.createMany({
    data: [
      { title: 'E-commerce Platform', description: 'A full-featured online store.' },
      { title: 'Corporate Website', description: 'A professional site for a law firm.' },
    ],
  })

  // Seed Team Members
  await prisma.teamMember.createMany({
    data: [
      { name: 'John Doe', role: 'CEO', bio: 'Expert in business strategy.' },
      { name: 'Jane Smith', role: 'CTO', bio: 'Passionate about technology.' },
    ],
  })

  console.log('Seeding complete!')
}

main()
  .catch((e) => {
    console.error(e)
    process.exit(1)
  })
  .finally(async () => {
    await prisma.$disconnect()
  })
